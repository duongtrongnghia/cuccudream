<?php

namespace App\Livewire;

use App\Models\ChallengeTask;
use App\Models\Expedition;
use App\Models\ExpeditionCheckin;
use App\Models\ExpeditionMember;
use App\Notifications\GenericNotification;
use App\Services\XpService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class ChallengeDetail extends Component
{
    use WithPagination;

    public Expedition $expedition;
    #[Rule('required|min:5|max:1000')]
    public string $checkinContent = '';

    // Pagination for report & submissions
    public int $reportPage = 1;
    public int $submissionPage = 1;
    private const PER_PAGE = 10;

    public function mount(int $id): void
    {
        $this->expedition = Expedition::with(['creator', 'members.user', 'tasks'])->findOrFail($id);
    }

    // ─── Enrollment ─────────────────────────────────────────
    public function requestJoin(): void
    {
        if (!Auth::check()) return;
        $user = Auth::user();

        if ($this->expedition->members()->where('user_id', $user->id)->exists()) {
            $this->dispatch('toast', message: 'Bạn đã đăng ký Challenge này rồi', type: 'error');
            return;
        }

        ExpeditionMember::create([
            'expedition_id' => $this->expedition->id,
            'user_id' => $user->id,
            'class_at_join' => $user->class,
            'joined_at' => now(),
            'status' => 'pending',
        ]);

        // Notify all admins
        \App\Models\User::where('is_admin', true)->each(function ($admin) use ($user) {
            $admin->notify(new GenericNotification(
                '★', $user->name . ' đăng ký tham gia ' . $this->expedition->title,
                route('challenge.show', $this->expedition->id)
            ));
        });

        $this->dispatch('toast', message: 'Đã gửi yêu cầu tham gia! Vui lòng chờ Admin duyệt.', type: 'success');
        $this->expedition->refresh();
    }

    public function cancelRequest(): void
    {
        if (!Auth::check()) return;
        $member = $this->expedition->members()
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->first();
        if (!$member) return;
        $member->delete();
        $this->dispatch('toast', message: 'Đã rút yêu cầu tham gia', type: 'success');
        $this->expedition->refresh();
    }

    public function approveRequest(int $memberId): void
    {
        if (!Auth::check() || !Auth::user()->is_admin) return;

        $member = ExpeditionMember::where('id', $memberId)
            ->where('expedition_id', $this->expedition->id)
            ->where('status', 'pending')
            ->firstOrFail();

        $member->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
            // personal_starts_at NOT set yet — user must click "Bắt đầu"
        ]);

        $member->user->notify(new GenericNotification(
            '✅', 'Bạn đã được duyệt tham gia ' . $this->expedition->title . '! Bấm "Bắt đầu" khi bạn sẵn sàng.',
            route('challenge.show', $this->expedition->id)
        ));

        $this->dispatch('toast', message: 'Đã duyệt ' . $member->user->name, type: 'success');
        $this->expedition->refresh();
    }

    public function rejectRequest(int $memberId): void
    {
        if (!Auth::check() || !Auth::user()->is_admin) return;

        $member = ExpeditionMember::where('id', $memberId)
            ->where('expedition_id', $this->expedition->id)
            ->where('status', 'pending')
            ->firstOrFail();

        $member->update(['status' => 'rejected']);

        $member->user->notify(new GenericNotification(
            '❌', 'Yêu cầu tham gia ' . $this->expedition->title . ' đã bị từ chối.',
        ));

        $this->dispatch('toast', message: 'Đã từ chối ' . $member->user->name, type: 'success');
        $this->expedition->refresh();
    }

    // ─── Start challenge (user clicks after approval) ──────
    public function startMyChallenge(): void
    {
        if (!Auth::check()) return;
        $member = $this->expedition->members()
            ->where('user_id', Auth::id())
            ->where('status', 'approved')
            ->whereNull('personal_starts_at')
            ->first();
        if (!$member) return;

        $member->update(['personal_starts_at' => now()]);
        $this->dispatch('toast', message: 'Challenge đã bắt đầu! Chúc bạn chinh phục thành công!', type: 'success');
        $this->expedition->refresh();
    }

    // ─── Check-in ───────────────────────────────────────────
    public function checkin(): void
    {
        if (!Auth::check()) return;
        $this->validate();

        $user = Auth::user();
        $member = $this->getApprovedMember($user->id);
        if (!$member) {
            $this->addError('checkinContent', 'Bạn chưa được duyệt tham gia Challenge này.');
            return;
        }

        $alreadyToday = ExpeditionCheckin::where('expedition_id', $this->expedition->id)
            ->where('user_id', $user->id)
            ->whereDate('created_at', today())
            ->exists();

        if ($alreadyToday) {
            $this->addError('checkinContent', 'Bạn đã check-in hôm nay rồi.');
            return;
        }

        ExpeditionCheckin::create([
            'expedition_id' => $this->expedition->id,
            'user_id' => $user->id,
            'content' => $this->checkinContent,
        ]);

        $member->update(['last_checkin_at' => now(), 'consecutive_missed_days' => 0]);

        app(XpService::class)->award(
            $user, 'expedition_checkin', 1.0,
            'Check-in Challenge: ' . $this->expedition->title,
            $this->expedition
        );

        $this->reset('checkinContent');
        $this->expedition->refresh();
    }

    // ─── Tasks ──────────────────────────────────────────────
    public array $taskEvidence = [];

    public function completeTask(int $taskId): void
    {
        if (!Auth::check()) return;
        $user = Auth::user();

        $task = ChallengeTask::where('id', $taskId)
            ->where('expedition_id', $this->expedition->id)
            ->firstOrFail();

        $member = $this->getApprovedMember($user->id);
        if (!$member) return;

        $currentDay = $this->getCurrentDayForMember($member);
        if ($task->day_number > $currentDay) return;

        if ($task->completedByUsers()->where('user_id', $user->id)->exists()) return;

        $evidence = $this->taskEvidence[$taskId] ?? '';
        if (blank($evidence)) {
            $this->dispatch('toast', message: 'Vui lòng cung cấp bằng chứng hoàn thành!', type: 'error');
            return;
        }

        $late = $this->isTaskLate($member, $task->day_number);
        $task->completedByUsers()->attach($user->id, [
            'evidence' => $evidence,
            'is_late' => $late,
        ]);

        app(XpService::class)->award(
            $user, 'expedition_checkin', 1.0,
            'Hoàn thành nhiệm vụ ngày ' . $task->day_number . ': ' . $task->title,
            $this->expedition
        );

        $this->taskEvidence[$taskId] = '';
        $msg = 'Hoàn thành nhiệm vụ ngày ' . $task->day_number . '!';
        if ($late) $msg .= ' (Nộp trễ)';
        $this->dispatch('toast', message: $msg, type: $late ? 'warning' : 'success');
    }

    // ─── Resubmit rejected task ───────────────────────────────
    public const RESUBMIT_FEE = 34000; // VND — fee for 2nd+ resubmit

    public function resubmitTask(int $taskId): void
    {
        if (!Auth::check()) return;
        $user = Auth::user();

        $task = ChallengeTask::where('id', $taskId)
            ->where('expedition_id', $this->expedition->id)
            ->firstOrFail();

        $existing = \DB::table('challenge_task_completions')
            ->where('challenge_task_id', $taskId)
            ->where('user_id', $user->id)
            ->where('status', 'rejected')
            ->first();
        if (!$existing) return;

        // 2nd+ reject requires payment
        if ($existing->reject_count >= 2 && !$existing->resubmit_payment_ref) {
            $this->dispatch('toast', message: 'Cần thanh toán 34.000đ để nộp lại. Chuyển khoản theo hướng dẫn bên dưới.', type: 'error');
            return;
        }

        $evidence = $this->taskEvidence[$taskId] ?? '';
        if (blank($evidence)) {
            $this->dispatch('toast', message: 'Vui lòng cung cấp bằng chứng mới!', type: 'error');
            return;
        }

        $member = $this->getApprovedMember($user->id);
        $late = $this->isTaskLate($member, $task->day_number);

        \DB::table('challenge_task_completions')
            ->where('id', $existing->id)
            ->update([
                'evidence' => $evidence,
                'status' => 'pending',
                'is_late' => $late,
                'reviewed_by' => null,
                'reviewed_at' => null,
                'review_note' => null,
                'resubmit_payment_ref' => null,
                'updated_at' => now(),
            ]);

        $this->taskEvidence[$taskId] = '';
        $this->dispatch('toast', message: 'Đã nộp lại bài! Chờ admin duyệt.', type: 'success');
    }

    // ─── Video feedback ────────────────────────────────────────
    public string $videoFeedbackUrl = '';

    public function submitVideoFeedback(): void
    {
        if (!Auth::check()) return;
        $member = $this->getApprovedMember(Auth::id());
        if (!$member) return;
        if (blank($this->videoFeedbackUrl)) {
            $this->dispatch('toast', message: 'Vui lòng paste link video!', type: 'error');
            return;
        }

        $member->update([
            'video_feedback_url' => $this->videoFeedbackUrl,
            'video_feedback_status' => 'pending',
            'video_feedback_at' => now(),
        ]);

        // Notify admins
        \App\Models\User::where('is_admin', true)->each(function ($admin) {
            $admin->notify(new GenericNotification(
                '▶', Auth::user()->name . ' gửi Video Feedback cho ' . $this->expedition->title,
                route('challenge.show', $this->expedition->id)
            ));
        });

        $this->videoFeedbackUrl = '';
        $this->dispatch('toast', message: 'Đã gửi Video Feedback! Chờ admin duyệt.', type: 'success');
        $this->expedition->refresh();
    }

    public function approveVideoFeedback(int $memberId): void
    {
        if (!Auth::check() || !Auth::user()->is_admin) return;
        $member = ExpeditionMember::findOrFail($memberId);
        $member->update([
            'video_feedback_status' => 'approved',
            'video_feedback_note' => 'Video đạt yêu cầu! Bạn nhận được 1 buổi training trị giá $500 từ team core KP3.',
        ]);
        $member->user->notify(new GenericNotification(
            '★', 'Video Feedback được duyệt! Bạn nhận được 1 buổi meeting training trị giá $500 từ team core KP3.',
            route('challenge.show', $this->expedition->id)
        ));
        $this->dispatch('toast', message: 'Đã duyệt video feedback!', type: 'success');
    }

    public function rejectVideoFeedback(int $memberId, string $note = ''): void
    {
        if (!Auth::check() || !Auth::user()->is_admin) return;
        $member = ExpeditionMember::findOrFail($memberId);
        $member->update([
            'video_feedback_status' => 'rejected',
            'video_feedback_note' => $note ?: 'Video chưa đạt yêu cầu. Hãy quay lại video chân thật và đầy cảm xúc hơn.',
            'video_feedback_url' => null, // allow resubmit
        ]);
        $member->user->notify(new GenericNotification(
            '✗', 'Video Feedback chưa đạt: ' . ($note ?: 'Hãy quay lại video chân thật hơn.'),
            route('challenge.show', $this->expedition->id)
        ));
        $this->dispatch('toast', message: 'Đã từ chối video feedback', type: 'success');
    }

    // ─── Admin: update task video/SOP ────────────────────────
    public ?int $editingTaskId = null;
    public string $editTaskTitle = '';
    public string $editTaskDesc = '';
    public string $editTaskVideo = '';
    public string $editTaskMeetingAt = '';
    public string $editTaskSop = '';
    public string $editTaskEvidenceLabel = '';
    public string $editTaskAdminNote = '';

    public function startEditTask(int $taskId): void
    {
        if (!Auth::check() || !Auth::user()->is_admin) return;
        $task = ChallengeTask::findOrFail($taskId);
        $this->editingTaskId = $taskId;
        $this->editTaskTitle = $task->title ?? '';
        $this->editTaskDesc = $task->description ?? '';
        $this->editTaskVideo = $task->video_url ?? '';
        $this->editTaskMeetingAt = $task->meeting_at ? $task->meeting_at->timezone('Asia/Ho_Chi_Minh')->format('Y-m-d\TH:i') : '';
        $this->editTaskSop = $task->sop_content ?? '';
        $this->editTaskEvidenceLabel = $task->evidence_label ?? '';
        $this->editTaskAdminNote = $task->admin_note ?? '';
    }

    public function saveEditTask(): void
    {
        if (!Auth::check() || !Auth::user()->is_admin || !$this->editingTaskId) return;
        ChallengeTask::where('id', $this->editingTaskId)->update([
            'title' => $this->editTaskTitle ?: 'Nhiệm vụ',
            'description' => $this->editTaskDesc ?: null,
            'video_url' => $this->editTaskVideo ?: null,
            'meeting_at' => $this->editTaskMeetingAt
                ? \Carbon\Carbon::parse($this->editTaskMeetingAt, 'Asia/Ho_Chi_Minh')->utc()
                : null,
            'sop_content' => $this->editTaskSop ?: null,
            'evidence_label' => $this->editTaskEvidenceLabel ?: null,
            'admin_note' => $this->editTaskAdminNote ?: null,
        ]);
        $this->editingTaskId = null;
        $this->editTaskVideo = '';
        $this->editTaskSop = '';
        $this->expedition->refresh();
        $this->dispatch('toast', message: 'Đã cập nhật nhiệm vụ!', type: 'success');
    }

    public function cancelEditTask(): void
    {
        $this->editingTaskId = null;
    }

    // ─── Admin: review submissions ──────────────────────────
    public function approveAllPending(): void
    {
        if (!Auth::check() || !Auth::user()->is_admin) return;
        $taskIds = $this->expedition->tasks()->pluck('id');

        // Get user IDs before updating
        $userIds = \DB::table('challenge_task_completions')
            ->whereIn('challenge_task_id', $taskIds)
            ->where('status', 'pending')
            ->distinct()
            ->pluck('user_id');

        $count = \DB::table('challenge_task_completions')
            ->whereIn('challenge_task_id', $taskIds)
            ->where('status', 'pending')
            ->update([
                'status' => 'approved',
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
            ]);

        // Notify all affected users
        \App\Models\User::whereIn('id', $userIds)->each(function ($user) {
            $user->notify(new GenericNotification(
                '✓', 'Bài nộp đã được duyệt!',
                route('challenge.show', $this->expedition->id)
            ));
        });

        $this->dispatch('toast', message: "Đã duyệt {$count} bài nộp!", type: 'success');
    }

    public function approveSubmission(int $completionId): void
    {
        if (!Auth::check() || !Auth::user()->is_admin) return;
        $completion = \DB::table('challenge_task_completions')->where('id', $completionId)->first();
        if (!$completion) return;

        \DB::table('challenge_task_completions')->where('id', $completionId)->update([
            'status' => 'approved',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        $user = \App\Models\User::find($completion->user_id);
        if ($user) {
            $user->notify(new GenericNotification(
                '✓', 'Bài nộp đã được duyệt!',
                route('challenge.show', $this->expedition->id)
            ));
        }
        $this->dispatch('toast', message: 'Đã duyệt bài nộp!', type: 'success');
    }

    public function rejectSubmission(int $completionId, string $note = ''): void
    {
        if (!Auth::check() || !Auth::user()->is_admin) return;
        $completion = \DB::table('challenge_task_completions')->where('id', $completionId)->first();
        if (!$completion) return;

        $rejectNote = $note ?: 'Bài nộp chưa đạt yêu cầu';
        \DB::table('challenge_task_completions')->where('id', $completionId)->update([
            'status' => 'rejected',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'review_note' => $rejectNote,
            'reject_count' => \DB::raw('reject_count + 1'),
        ]);

        // Notify user
        $user = \App\Models\User::find($completion->user_id);
        if ($user) {
            $user->notify(new GenericNotification(
                '✗',
                'Bài nộp bị từ chối: ' . $rejectNote . '. Vui lòng nộp lại.',
                route('challenge.show', $this->expedition->id)
            ));
        }

        $this->dispatch('toast', message: 'Đã từ chối bài nộp', type: 'success');
    }

    // ─── Helpers ─────────────────────────────────────────────
    private function getApprovedMember(int $userId): ?ExpeditionMember
    {
        return $this->expedition->members()
            ->where('user_id', $userId)
            ->whereIn('status', ['approved', 'paid'])
            ->whereNull('kicked_at')
            ->first();
    }

    /**
     * Current unlocked day for a member.
     * Uses 24h windows from personal_starts_at (not calendar days).
     * Must complete task N before task N+1 unlocks.
     */
    private function getCurrentDayForMember(?ExpeditionMember $member): int
    {
        if (!$member || !$member->personal_starts_at) return 0;

        // 24h windows from exact start time (not calendar days)
        $hoursElapsed = $member->personal_starts_at->diffInHours(now());
        $timeDay = min((int) floor($hoursElapsed / 24) + 1, $this->expedition->required_days);

        // Must finish task N to unlock N+1
        $completedCount = \DB::table('challenge_task_completions')
            ->join('challenge_tasks', 'challenge_tasks.id', '=', 'challenge_task_completions.challenge_task_id')
            ->where('challenge_tasks.expedition_id', $this->expedition->id)
            ->where('challenge_task_completions.user_id', $member->user_id)
            ->count();

        return min($timeDay, $completedCount + 1);
    }

    /**
     * Check if a task submission is late (submitted after its 24h window).
     * Task N window: starts_at + (N-1)*24h → starts_at + N*24h
     */
    private function isTaskLate(?ExpeditionMember $member, int $dayNumber): bool
    {
        if (!$member || !$member->personal_starts_at) return false;
        $deadline = $member->personal_starts_at->copy()->addHours($dayNumber * 24);
        return now()->greaterThan($deadline);
    }

    public function render()
    {
        $user = Auth::user();
        $myMember = $user
            ? $this->expedition->members()->where('user_id', $user->id)->first()
            : null;

        $isApproved = $myMember && in_array($myMember->status, ['approved', 'paid']) && !$myMember->kicked_at;
        $isPending = $myMember && $myMember->status === 'pending';
        $currentDay = $isApproved ? $this->getCurrentDayForMember($myMember) : 0;

        $approvedMembers = $this->expedition->members()
            ->whereIn('status', ['approved', 'paid'])
            ->whereNull('kicked_at')
            ->with('user')
            ->get();

        $pendingMembers = Auth::check() && Auth::user()->is_admin
            ? $this->expedition->members()->where('status', 'pending')->with('user')->get()
            : collect();

        $tasks = $this->expedition->tasks()->orderBy('day_number')->get();

        // Task submission history: user sees own, admin sees all (paginated)
        $submissionsPaginator = null;
        if ($tasks->count() > 0 && $user) {
            $submissionsPaginator = \DB::table('challenge_task_completions')
                ->join('challenge_tasks', 'challenge_tasks.id', '=', 'challenge_task_completions.challenge_task_id')
                ->join('users', 'users.id', '=', 'challenge_task_completions.user_id')
                ->where('challenge_tasks.expedition_id', $this->expedition->id)
                ->when(!$user->is_admin, fn($q) => $q->where('challenge_task_completions.user_id', $user->id))
                ->select('challenge_task_completions.id as completion_id',
                    'users.name', 'users.username', 'users.id as user_id', 'users.avatar',
                    'challenge_tasks.day_number', 'challenge_tasks.title as task_title',
                    'challenge_task_completions.evidence', 'challenge_task_completions.is_late',
                    'challenge_task_completions.status as review_status',
                    'challenge_task_completions.review_note', 'challenge_task_completions.created_at')
                ->orderByDesc('challenge_task_completions.created_at')
                ->paginate(self::PER_PAGE, ['*'], 'submissionPage', $this->submissionPage);
        }
        $completedTaskIds = [];
        $completedTaskCount = 0;
        $myCompletions = collect(); // user's own submissions keyed by task_id
        if ($user && $tasks->count() > 0) {
            $myCompletions = \DB::table('challenge_task_completions')
                ->where('user_id', $user->id)
                ->whereIn('challenge_task_id', $tasks->pluck('id'))
                ->get()
                ->keyBy('challenge_task_id');
            $completedTaskIds = $myCompletions->keys()->toArray();
            $completedTaskCount = count($completedTaskIds);
        }

        $personalDaysLeft = null;
        if ($isApproved && $myMember->personal_starts_at) {
            $endsAt = $myMember->personal_starts_at->copy()->addDays($this->expedition->required_days);
            $personalDaysLeft = max(0, (int) now()->startOfDay()->diffInDays($endsAt->startOfDay()));
        }

        // Admin: member progress report
        $memberReport = collect();
        if (Auth::check() && Auth::user()->is_admin && $tasks->count() > 0 && $approvedMembers->count() > 0) {
            $allCompletions = \DB::table('challenge_task_completions')
                ->whereIn('challenge_task_id', $tasks->pluck('id'))
                ->get()
                ->groupBy('user_id');

            $memberReport = $approvedMembers->map(function ($member) use ($tasks, $allCompletions) {
                $memberDay = $this->getCurrentDayForMember($member);
                $completed = $allCompletions->get($member->user_id, collect());
                $completedCount = $completed->count();
                $lateCount = $completed->where('is_late', true)->count();
                $rejectedCount = $completed->where('status', 'rejected')->count();

                // Only count expired deadlines as missed (not the current active day)
                // Rejected submissions don't count as completed
                $validCount = $completedCount - $rejectedCount;
                $expiredDays = 0;
                if ($member->personal_starts_at) {
                    $hoursElapsed = $member->personal_starts_at->diffInHours(now());
                    $expiredDays = min((int) floor($hoursElapsed / 24), $this->expedition->required_days);
                }
                $missedCount = max(0, $expiredDays - $validCount);

                return (object) [
                    'member' => $member,
                    'current_day' => $memberDay,
                    'completed' => $validCount,
                    'rejected' => $rejectedCount,
                    'late' => $lateCount,
                    'missed' => $missedCount,
                    'total' => $tasks->count(),
                    'pct' => $tasks->count() > 0 ? round($validCount / $tasks->count() * 100) : 0,
                ];
            })->sortBy([
                ['missed', 'asc'],       // ít miss trước
                ['rejected', 'asc'],     // ít bị reject trước
                ['completed', 'desc'],   // hoàn thành nhiều trước
            ])->values();
        }

        // Paginate member report
        $reportTotal = $memberReport->count();
        $reportLastPage = max(1, (int) ceil($reportTotal / self::PER_PAGE));
        $this->reportPage = min($this->reportPage, $reportLastPage);
        $memberReportPage = $memberReport->forPage($this->reportPage, self::PER_PAGE);

        // Task deadlines for current user (24h windows from personal_starts_at)
        $taskDeadlines = [];
        if ($isApproved && $myMember->personal_starts_at) {
            foreach ($tasks as $task) {
                $taskDeadlines[$task->id] = $myMember->personal_starts_at->copy()
                    ->addHours($task->day_number * 24);
            }
        }

        // Late status for completed tasks
        $lateTaskIds = [];
        if ($user && $tasks->count() > 0) {
            $lateTaskIds = \DB::table('challenge_task_completions')
                ->where('user_id', $user->id)
                ->whereIn('challenge_task_id', $tasks->pluck('id'))
                ->where('is_late', true)
                ->pluck('challenge_task_id')->toArray();
        }

        return view('livewire.challenge-detail', [
            'approvedMembers' => $approvedMembers,
            'pendingMembers' => $pendingMembers,
            'submissions' => $submissionsPaginator,
            'isApproved' => $isApproved,
            'isPending' => $isPending,
            'myMember' => $myMember,
            'tasks' => $tasks,
            'completedTaskIds' => $completedTaskIds,
            'completedTaskCount' => $completedTaskCount,
            'myCompletions' => $myCompletions,
            'currentDay' => $currentDay,
            'personalDaysLeft' => $personalDaysLeft,
            'memberReport' => $memberReportPage,
            'reportTotal' => $reportTotal,
            'reportLastPage' => $reportLastPage,
            'taskDeadlines' => $taskDeadlines,
            'lateTaskIds' => $lateTaskIds,
        ])->layout('layouts.app', ['title' => $this->expedition->title . ' — Challenge']);
    }
}
