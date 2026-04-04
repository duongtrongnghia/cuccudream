<?php

namespace App\Console\Commands;

use App\Models\Expedition;
use App\Models\ExpeditionCheckin;
use App\Notifications\GenericNotification;
use App\Services\XpService;
use Illuminate\Console\Command;

class ProcessChallenges extends Command
{
    protected $signature = 'aip:process-challenges';
    protected $description = 'Auto-complete/fail expired challenges and kick inactive members';

    public function handle(): void
    {
        $xp = app(XpService::class);

        $expired = Expedition::where('status', 'active')
            ->where('ends_at', '<', now())
            ->with(['members.user', 'creator'])
            ->get();

        foreach ($expired as $ch) {
            $activeMembers = $ch->members()->whereNull('kicked_at')->get();
            $allCompleted = $activeMembers->every(fn($m) => $m->consecutive_missed_days < 3);

            if ($allCompleted && $activeMembers->count() >= 2) {
                $ch->complete();
                foreach ($activeMembers as $member) {
                    $xp->award($member->user, 'expedition_complete', $ch->getXpBonusMultiplier(), 'Hoàn thành Challenge: ' . $ch->title, $ch);
                    $member->update(['completed_at' => now()]);
                }
                $xp->award($ch->creator, 'expedition_captain', 1.0, 'Leader hoàn thành: ' . $ch->title, $ch);
                $this->info("Completed: {$ch->title}");
            } else {
                $ch->fail();
                $this->info("Failed: {$ch->title}");
            }
        }

        $active = Expedition::where('status', 'active')->with(['members.user', 'tasks'])->get();
        foreach ($active as $ch) {
            $taskIds = $ch->tasks->pluck('id');
            $members = $ch->members()->whereNull('kicked_at')->whereNotNull('personal_starts_at')->get();

            foreach ($members as $member) {
                // Check if member submitted any task yesterday (via challenge_task_completions)
                $submittedYesterday = \DB::table('challenge_task_completions')
                    ->whereIn('challenge_task_id', $taskIds)
                    ->where('user_id', $member->user_id)
                    ->whereDate('created_at', now()->subDay())
                    ->exists();

                // Also check old checkin system as fallback
                $checkedInYesterday = ExpeditionCheckin::where('expedition_id', $ch->id)
                    ->where('user_id', $member->user_id)
                    ->whereDate('created_at', now()->subDay())
                    ->exists();

                if ($submittedYesterday || $checkedInYesterday) {
                    $member->update(['consecutive_missed_days' => 0]);
                } else {
                    $member->increment('consecutive_missed_days');
                }

                if ($member->consecutive_missed_days >= 3) {
                    $member->update(['kicked_at' => now()]);
                    $member->user->notify(new GenericNotification('★', 'Bạn bị loại khỏi Challenge "' . $ch->title . '" do vắng mặt 3 ngày liên tiếp'));
                    $this->info("Kicked {$member->user->name} from {$ch->title}");
                }
            }
        }
    }
}
