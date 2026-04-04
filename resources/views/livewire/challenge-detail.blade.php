<div>
    <div class="mb-4">
        <a href="{{ route('challenge') }}" style="font-size:0.8rem; color:#636E72;">← Quay lại Challenge</a>
    </div>

    {{-- Header --}}
    <div class="card mb-4">
        <div class="flex items-start justify-between mb-3">
            <div>
                <h1 style="font-size:1.25rem; font-weight:800; color:#1A1A1A;">{{ $expedition->title }}</h1>
                <div class="flex items-center gap-2 mt-1">
                    <span class="badge difficulty-{{ $expedition->difficulty }}">{{ $expedition->difficulty_label }}</span>
                    @if($expedition->price > 0)
                    <span class="badge" style="background:#FEF3C7; color:#92400E; border:1px solid #FDE68A;">{{ number_format($expedition->price, 0, ',', '.') }}đ</span>
                    @else
                    <span class="badge" style="background:#D1FAE5; color:#065F46;">Miễn phí</span>
                    @endif
                </div>
            </div>
        </div>

        <div style="background:#FFF9F0; border-radius:0.5rem; padding:0.75rem; margin-bottom:1rem;">
            <p style="font-size:0.7rem; color:#636E72; margin-bottom:0.25rem;"> MỤC TIÊU</p>
            <p style="font-size:0.9rem; color:#FF6B6B; font-weight:700;">{{ $expedition->boss_name }}</p>
        </div>

        @if($expedition->description)
        <p style="font-size:0.85rem; color:#2E2E2E; margin-bottom:1rem; line-height:1.5; white-space:pre-line;">{{ $expedition->description }}</p>
        @endif

        <div class="flex flex-wrap gap-4 mb-4">
            <div>
                <p style="font-size:0.7rem; color:#636E72;">Leader</p>
                <div class="flex items-center gap-2 mt-1">
                    <img src="{{ $expedition->creator->avatar_url }}" class="avatar w-6 h-6" alt="">
                    <a href="{{ route('profile', $expedition->creator->username ?? $expedition->creator->id) }}" style="font-size:0.8rem; font-weight:600; color:#1A1A1A;">{{ $expedition->creator->name }}</a>
                </div>
            </div>
            <div>
                <p style="font-size:0.7rem; color:#636E72;">Thành viên</p>
                <p style="font-size:0.9rem; font-weight:700; color:#1A1A1A; margin-top:0.25rem;">{{ $approvedMembers->count() }}</p>
            </div>
            <div>
                <p style="font-size:0.7rem; color:#636E72;">Thời gian</p>
                <p style="font-size:0.9rem; font-weight:700; color:#1A1A1A; margin-top:0.25rem;">{{ $expedition->required_days }} ngày</p>
            </div>
        </div>

        {{-- Join / Status buttons --}}
        <div class="flex flex-wrap gap-2">
            @auth
            @if(!$isApproved && !$isPending)
            <button wire:click="requestJoin" class="btn btn-primary">Đăng ký tham gia</button>
            @elseif($isPending)
            <div class="flex items-center gap-2 px-4 py-2 rounded-lg" style="background:#FEF3C7; border:1px solid #FDE68A;">
                <span style="font-size:0.85rem;">⏳</span>
                <span style="font-size:0.8rem; font-weight:600; color:#92400E;">Đang chờ Admin duyệt</span>
                <button wire:click="cancelRequest" wire:confirm="Rút yêu cầu tham gia?" style="font-size:0.75rem; color:#991B1B; cursor:pointer; margin-left:0.5rem; font-weight:500;">Rút lại</button>
            </div>
            @elseif($isApproved && $personalDaysLeft === null)
            {{-- Approved but not started yet --}}
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 px-4 py-2 rounded-lg" style="background:#D1FAE5; border:1px solid #A7F3D0;">
                    <span style="font-size:0.85rem;">✓</span>
                    <span style="font-size:0.8rem; font-weight:600; color:#065F46;">Đã được duyệt</span>
                </div>
                <button wire:click="startMyChallenge" wire:confirm="Bắt đầu thử thách? Nhiệm vụ sẽ unlock hàng ngày từ bây giờ." class="btn btn-primary" style="font-size:0.85rem; padding:0.5rem 1.25rem;">
                    🚀 Bắt đầu thử thách
                </button>
            </div>
            @elseif($isApproved && $personalDaysLeft !== null)
            <div class="flex items-center gap-2 px-4 py-2 rounded-lg" style="background:#D1FAE5; border:1px solid #A7F3D0;">
                <span style="font-size:0.85rem;">✓</span>
                <span style="font-size:0.8rem; font-weight:600; color:#065F46;">Đang tham gia · Ngày {{ $currentDay }}/{{ $expedition->required_days }} · Còn {{ $personalDaysLeft }} ngày</span>
            </div>

            {{-- Video Feedback inline --}}
            @if($myMember)
            @php $vfStatus = $myMember->video_feedback_status; @endphp
            <div style="background:#FFFBEB; border:1px solid #FDE68A; border-radius:0.5rem; padding:0.75rem; margin-top:0.75rem;">
                @if($vfStatus === 'approved')
                <p style="font-size:0.8rem; font-weight:600; color:#065F46;">✓ Video Feedback đã duyệt — Bạn nhận 1 buổi training $500 từ team core KP3!</p>
                @elseif($vfStatus === 'pending')
                <p style="font-size:0.8rem; font-weight:600; color:#92400E;">▶ Video Feedback đang chờ duyệt...</p>
                @else
                <p style="font-size:0.8rem; font-weight:700; color:#92400E; margin-bottom:0.375rem;">▶ Gửi Video Feedback — Nhận buổi training $500</p>
                <p style="font-size:0.75rem; color:#636E72; margin-bottom:0.5rem;">Quay video cảm nhận chân thật, đầy cảm xúc → nhận 1 buổi meeting training trị giá <strong>$500</strong> từ team core KP3.</p>
                @if($vfStatus === 'rejected')
                <p style="font-size:0.75rem; color:#DC2626; margin-bottom:0.375rem;">✗ {{ $myMember->video_feedback_note }}</p>
                @endif
                <div class="flex gap-2">
                    <input wire:model="videoFeedbackUrl" type="url" class="input" placeholder="Paste link video..." style="font-size:0.8rem; flex:1;">
                    <button wire:click="submitVideoFeedback" class="btn btn-primary" style="font-size:0.8rem; white-space:nowrap;">Gửi</button>
                </div>
                @endif
            </div>
            @endif

            @endif
            @else
            <a href="{{ route('login') }}" class="btn btn-primary">Đăng nhập để tham gia</a>
            @endauth
        </div>

        {{-- Payment info --}}
        @if($expedition->price > 0 && !$isApproved && !$isPending)
        <div style="margin-top:1rem; padding:1rem; background:#F0FDF4; border:1px solid #A5D6A7; border-radius:0.75rem;">
            <p style="font-size:0.85rem; font-weight:700; color:#E85555; margin-bottom:0.5rem;">▣ Thanh toán</p>
            <p style="font-size:0.8rem; color:#636E72; margin-bottom:0.5rem;">Chuyển khoản qua SePay với nội dung ghi đúng username của bạn.</p>
            <p style="font-size:1.1rem; font-weight:800; color:#FF6B6B;">{{ number_format($expedition->price, 0, ',', '.') }}đ</p>
            <p style="font-size:0.75rem; color:#636E72; margin-top:0.375rem;">Admin sẽ xác nhận thanh toán và duyệt tham gia cho bạn.</p>
        </div>
        @endif
    </div>

    {{-- Admin: Pending requests --}}
    @if($pendingMembers->count() > 0)
    <div class="card mb-4" style="border-left:3px solid #F59E0B;">
        <h2 style="font-size:0.9rem; font-weight:700; color:#92400E; margin-bottom:0.75rem;">⏳ Yêu cầu tham gia ({{ $pendingMembers->count() }})</h2>
        <div class="flex flex-col gap-2">
            @foreach($pendingMembers as $pm)
            <div class="flex items-center gap-3 py-2 {{ !$loop->last ? 'border-b' : '' }}" style="{{ !$loop->last ? 'border-color:#E1E1E1;' : '' }}">
                <img src="{{ $pm->user->avatar_url }}" class="avatar w-8 h-8" alt="">
                <div style="flex:1;">
                    <a href="{{ route('profile', $pm->user->username ?? $pm->user->id) }}" style="font-size:0.8rem; font-weight:600; color:#1A1A1A;">{{ $pm->user->name }}</a>
                    <p style="font-size:0.65rem; color:#636E72;">{{ $pm->joined_at->diffForHumans() }}</p>
                </div>
                <div class="flex gap-1">
                    <button wire:click="approveRequest({{ $pm->id }})" class="btn btn-primary" style="font-size:0.7rem; padding:0.25rem 0.625rem;">Duyệt</button>
                    <button wire:click="rejectRequest({{ $pm->id }})" class="btn btn-ghost" style="font-size:0.7rem; padding:0.25rem 0.625rem; color:#991B1B;">Từ chối</button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Daily Tasks --}}
    @if($tasks->count() > 0 && $isApproved)
    <div class="card mb-4">
        <div class="flex items-center justify-between mb-3">
            <h2 style="font-size:0.9rem; font-weight:700; color:#1A1A1A;">Nhiệm vụ hàng ngày</h2>
            <span style="font-size:0.75rem; color:#636E72;">{{ $completedTaskCount }}/{{ $tasks->count() }} hoàn thành</span>
        </div>

        <div style="height:6px; background:#EEECE9; border-radius:3px; margin-bottom:1rem; overflow:hidden;">
            <div style="height:100%; background:#FF6B6B; border-radius:3px; width:{{ $tasks->count() > 0 ? round($completedTaskCount / $tasks->count() * 100) : 0 }}%; transition:width 0.3s;"></div>
        </div>

        <div class="flex flex-col gap-3">
            @foreach($tasks as $task)
            @php
                $isUnlocked = $task->day_number <= $currentDay;
                $isCompleted = in_array($task->id, $completedTaskIds);
                $isLate = in_array($task->id, $lateTaskIds ?? []);
                $deadline = $taskDeadlines[$task->id] ?? null;
                $isOverdue = $deadline && now()->greaterThan($deadline) && !$isCompleted;
            @endphp
            <div x-data="{ expanded: false }"
                 class="py-3 {{ !$loop->last ? 'border-b' : '' }}" style="{{ !$loop->last ? 'border-color:#E1E1E1;' : '' }} {{ !$isUnlocked ? 'opacity:0.5;' : '' }}">

                {{-- Task header --}}
                <div class="flex items-start gap-3" @click="if({{ $isUnlocked ? 'true' : 'false' }}) expanded = !expanded" style="{{ $isUnlocked ? 'cursor:pointer;' : '' }}">
                    @if($isCompleted && $isLate)
                    <span style="font-size:1rem; margin-top:0.1rem;">!</span>
                    @elseif($isCompleted)
                    <span style="font-size:1rem; margin-top:0.1rem;">✓</span>
                    @elseif($isOverdue)
                    <span style="font-size:1rem; margin-top:0.1rem;">!</span>
                    @elseif(!$isUnlocked)
                    <span style="font-size:1rem; margin-top:0.1rem;">▪</span>
                    @else
                    <span style="font-size:1rem; margin-top:0.1rem;">▢</span>
                    @endif
                    <div style="flex:1;">
                        <div class="flex items-center gap-2">
                            <span style="font-size:0.7rem; font-weight:600; color:#FF6B6B; background:#E8F5E9; padding:0.125rem 0.5rem; border-radius:999px;">Ngày {{ $task->day_number }}</span>
                            @if($isCompleted && $isLate)
                            <span style="font-size:0.65rem; color:#D97706; font-weight:500;">Hoàn thành (Trễ)</span>
                            @elseif($isCompleted)
                            <span style="font-size:0.65rem; color:#059669; font-weight:500;">Hoàn thành</span>
                            @elseif($isOverdue)
                            <span style="font-size:0.65rem; color:#DC2626; font-weight:500;">Quá hạn</span>
                            @elseif($isUnlocked && $deadline)
                            <span style="font-size:0.65rem; color:#FF6B6B; font-weight:500;">Đang mở · Hạn {{ $deadline->timezone('Asia/Ho_Chi_Minh')->format('H:i d/m') }}</span>
                            @elseif($isUnlocked)
                            <span style="font-size:0.65rem; color:#FF6B6B; font-weight:500;">Đang mở</span>
                            @endif
                            @if($task->meeting_at)
                            <span style="font-size:0.6rem; color:#1A73E8; background:#E8F0FE; padding:0.1rem 0.375rem; border-radius:4px; font-weight:500;">▶ Meeting · {{ $task->meeting_at->timezone('Asia/Ho_Chi_Minh')->format('H:i d/m') }}</span>
                            @elseif($task->video_url && preg_match('/meet\.google|zoom\.us/i', $task->video_url))
                            <span style="font-size:0.6rem; color:#1A73E8; background:#E8F0FE; padding:0.1rem 0.375rem; border-radius:4px; font-weight:500;">▶ Meeting</span>
                            @endif
                        </div>
                        @if($isUnlocked || $isCompleted)
                        <p style="font-size:0.825rem; font-weight:600; color:#1A1A1A; margin-top:0.25rem;">{{ $task->title }}</p>
                        @else
                        <p style="font-size:0.825rem; font-weight:500; color:#636E72; margin-top:0.25rem; font-style:italic;">Nhiệm vụ này vẫn còn là một bí ẩn, sẽ được unlock sau</p>
                        @endif
                    </div>
                    @if($isUnlocked)
                    <svg x-bind:style="expanded ? 'transform:rotate(180deg)' : ''" class="w-4 h-4 transition-transform" style="color:#636E72; margin-top:0.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    @endif

                    {{-- Admin edit button --}}
                    @can('admin')
                    <button wire:click="startEditTask({{ $task->id }})" @click.stop style="color:#636E72; font-size:0.65rem; padding:0.125rem 0.375rem; cursor:pointer;" title="Sửa nhiệm vụ">✏️</button>
                    @endcan
                </div>

                {{-- Admin edit form --}}
                @if($editingTaskId === $task->id)
                <div style="margin-top:0.75rem; padding:0.75rem; background:#F0FDF4; border-radius:0.5rem;" @click.stop>
                    <label style="display:block; font-size:0.75rem; font-weight:600; color:#E85555; margin-bottom:0.25rem;">Tên nhiệm vụ</label>
                    <input wire:model="editTaskTitle" class="input" placeholder="VD: Tham gia Meeting Kick Off" style="font-size:0.8rem; margin-bottom:0.5rem;">
                    <label style="display:block; font-size:0.75rem; font-weight:600; color:#E85555; margin-bottom:0.25rem;">Mô tả ngắn</label>
                    <textarea wire:model="editTaskDesc" class="input" rows="2" placeholder="Mô tả nhiệm vụ..."
                        x-data x-init="$el.style.height = $el.scrollHeight + 'px'"
                        @input="$el.style.height = 'auto'; $el.style.height = $el.scrollHeight + 'px'"
                        style="overflow:hidden; resize:none; font-size:0.8rem; margin-bottom:0.5rem;"></textarea>
                    <label style="display:block; font-size:0.75rem; font-weight:600; color:#E85555; margin-bottom:0.25rem;">Video / Meeting URLs (mỗi dòng 1 link)</label>
                    <textarea wire:model="editTaskVideo" class="input" rows="3" placeholder="https://youtube.com/watch?v=...&#10;https://meet.google.com/...&#10;https://youtube.com/watch?v=..."
                        x-data x-init="$el.style.height = $el.scrollHeight + 'px'"
                        @input="$el.style.height = 'auto'; $el.style.height = $el.scrollHeight + 'px'"
                        style="overflow:hidden; resize:none; font-size:0.8rem; margin-bottom:0.5rem;"></textarea>
                    <label style="display:block; font-size:0.75rem; font-weight:600; color:#E85555; margin-bottom:0.25rem;">Lịch Meeting (giờ VN)</label>
                    <input wire:model="editTaskMeetingAt" type="datetime-local" class="input" style="font-size:0.8rem; margin-bottom:0.5rem;">
                    <label style="display:block; font-size:0.75rem; font-weight:600; color:#E85555; margin-bottom:0.25rem;">SOP / Hướng dẫn</label>
                    <textarea wire:model="editTaskSop" class="input" rows="4" placeholder="Viết hướng dẫn chi tiết..."
                        x-data x-init="$el.style.height = $el.scrollHeight + 'px'"
                        @input="$el.style.height = 'auto'; $el.style.height = $el.scrollHeight + 'px'"
                        style="overflow:hidden; resize:none; font-size:0.8rem; margin-bottom:0.5rem;"></textarea>
                    <label style="display:block; font-size:0.75rem; font-weight:600; color:#E85555; margin-bottom:0.25rem;">Yêu cầu bằng chứng</label>
                    <input wire:model="editTaskEvidenceLabel" class="input" placeholder="VD: Chụp ảnh màn hình các tool đã cài" style="font-size:0.8rem; margin-bottom:0.5rem;">
                    <label style="display:block; font-size:0.75rem; font-weight:600; color:#E85555; margin-bottom:0.25rem;">Nhắn nhủ cho thành viên</label>
                    <textarea wire:model="editTaskAdminNote" class="input" rows="2" placeholder="Lời nhắn từ admin hiện sau khi hoàn thành nhiệm vụ..." style="font-size:0.8rem; margin-bottom:0.5rem;"
                        x-data x-init="$el.style.height = $el.scrollHeight + 'px'"
                        @input="$el.style.height = 'auto'; $el.style.height = $el.scrollHeight + 'px'"
                        style="overflow:hidden; resize:none; font-size:0.8rem; margin-bottom:0.5rem;"></textarea>
                    <div class="flex gap-2">
                        <button wire:click="saveEditTask" class="btn btn-primary" style="font-size:0.75rem; padding:0.25rem 0.625rem;">Lưu</button>
                        <button wire:click="cancelEditTask" class="btn btn-ghost" style="font-size:0.75rem; padding:0.25rem 0.625rem;">Hủy</button>
                    </div>
                </div>
                @endif

                {{-- Expanded content --}}
                <div x-show="expanded" x-transition style="margin-top:0.75rem; margin-left:2rem;">
                    @if($task->description)
                    <p style="font-size:0.8rem; color:#636E72; line-height:1.5; margin-bottom:0.75rem;">{{ $task->description }}</p>
                    @endif

                    {{-- Videos / Links --}}
                    @if($task->video_url)
                    <div class="flex flex-col gap-2" style="margin-bottom:0.75rem;">
                        @foreach(array_filter(preg_split('/[\r\n]+/', $task->video_url)) as $videoUrl)
                        @php
                            $videoUrl = trim($videoUrl);
                            if (!$videoUrl) continue;
                            $ytId = null;
                            if (preg_match('/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/|shorts\/))([a-zA-Z0-9_-]{11})/', $videoUrl, $m)) {
                                $ytId = $m[1];
                            }
                            $isMeet = str_contains($videoUrl, 'meet.google.com');
                        @endphp
                        @if($ytId)
                        <div style="position:relative; padding-bottom:56.25%; height:0; overflow:hidden; border-radius:0.5rem;">
                            <iframe src="https://www.youtube.com/embed/{{ $ytId }}" style="position:absolute; top:0; left:0; width:100%; height:100%; border:0;" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                        @elseif($isMeet)
                        <a href="{{ $videoUrl }}" target="_blank" rel="noopener" class="flex items-center gap-2" style="font-size:0.8rem; font-weight:600; padding:0.625rem; border-radius:0.5rem; color:#1A73E8; background:#E8F0FE; border:1px solid #C2D7F4;">
                            <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24"><path d="M14.5 10.5L18.2 7.4C18.5 7.2 19 7.4 19 7.8V16.2C19 16.6 18.5 16.8 18.2 16.6L14.5 13.5V10.5Z" fill="#00832D"/><rect x="3" y="6" width="12" height="12" rx="1.5" fill="#00AC47"/><path d="M14.5 10.5L18.2 7.4C18.5 7.2 19 7.4 19 7.8V12H14.5V10.5Z" fill="#00832D"/><path d="M3 14.5H9V18H4.5C3.67 18 3 17.33 3 16.5V14.5Z" fill="#0066DA"/><path d="M9 14.5H14.5V18H9V14.5Z" fill="#00AC47"/><path d="M9 6H14.5V10.5H9V6Z" fill="#FFBA00"/><path d="M3 7.5C3 6.67 3.67 6 4.5 6H9V10.5H3V7.5Z" fill="#0066DA"/><path d="M3 10.5H9V14.5H3V10.5Z" fill="#0066DA"/></svg>
                            Tham gia Google Meet
                        </a>
                        @else
                        <a href="{{ $videoUrl }}" target="_blank" rel="noopener" class="flex items-center gap-2" style="font-size:0.8rem; font-weight:600; padding:0.625rem; border-radius:0.5rem; color:#FF6B6B; background:#F0FDF4; border:1px solid #A5D6A7;">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.84A1.5 1.5 0 004 4.11v11.78a1.5 1.5 0 002.3 1.27l9.344-5.891a1.5 1.5 0 000-2.538L6.3 2.84z"/></svg>
                            {{ Str::limit($videoUrl, 50) }}
                        </a>
                        @endif
                        @endforeach
                    </div>
                    @endif

                    {{-- SOP --}}
                    @if($task->sop_content)
                    <div style="background:#FFF9F0; border-radius:0.5rem; padding:0.75rem; margin-bottom:0.75rem; border-left:3px solid #FF6B6B;">
                        <p style="font-size:0.7rem; font-weight:700; color:#E85555; margin-bottom:0.375rem;">▤ SOP — Hướng dẫn</p>
                        <div style="font-size:0.8rem; color:#2E2E2E; line-height:1.6; white-space:pre-line;">{{ $task->sop_content }}</div>
                    </div>
                    @endif

                    {{-- Evidence: show submission or form --}}
                    @if($isCompleted)
                    @php
                        $myEvidence = $myCompletions[$task->id] ?? null;
                        $isRejected = $myEvidence && $myEvidence->status === 'rejected';
                    @endphp
                    @if($myEvidence)
                    <div style="background:{{ $isRejected ? '#FEF2F2' : '#F0FDF4' }}; border:1px solid {{ $isRejected ? '#FECACA' : '#A7F3D0' }}; border-radius:0.5rem; padding:0.75rem;">
                        <p style="font-size:0.7rem; font-weight:600; color:{{ $isRejected ? '#DC2626' : '#065F46' }}; margin-bottom:0.375rem;">
                            {{ $isRejected ? '✗ Bài bị từ chối' : '✓ Bài nộp của bạn' }}
                        </p>
                        <div style="font-size:0.8rem; color:#2E2E2E; line-height:1.5; white-space:pre-line;">{!! preg_replace('/(https?:\/\/[^\s]+)/', '<a href="$1" target="_blank" rel="noopener" style="color:#FF6B6B; text-decoration:underline;">$1</a>', e($myEvidence->evidence)) !!}</div>
                        @if($isRejected && $myEvidence->review_note)
                        <div style="background:#FFF; border:1px solid #FECACA; border-radius:0.375rem; padding:0.5rem; margin-top:0.5rem;">
                            <p style="font-size:0.7rem; font-weight:600; color:#DC2626; margin-bottom:0.125rem;">Lý do từ chối:</p>
                            <p style="font-size:0.8rem; color:#2E2E2E;">{{ $myEvidence->review_note }}</p>
                        </div>
                        @endif
                        <p style="font-size:0.65rem; color:#636E72; margin-top:0.375rem;">
                            Nộp lúc {{ \Carbon\Carbon::parse($myEvidence->created_at)->timezone('Asia/Ho_Chi_Minh')->format('H:i d/m') }}
                            @if($myEvidence->is_late) · <span style="color:#D97706;">Trễ</span> @endif
                        </p>
                    </div>
                    {{-- Resubmit form for rejected tasks --}}
                    @if($isRejected)
                    @php $needsPayment = $myEvidence->reject_count >= 2 && !$myEvidence->resubmit_payment_ref; @endphp
                    <div style="background:#FFFBEB; border:1px solid #FDE68A; border-radius:0.5rem; padding:0.75rem; margin-top:0.5rem;">
                        <p style="font-size:0.75rem; font-weight:600; color:#92400E; margin-bottom:0.375rem;">
                            Nộp lại bài {{ $myEvidence->reject_count >= 2 ? '(lần ' . $myEvidence->reject_count . ')' : '' }}
                        </p>
                        @if($needsPayment)
                        @php
                            $resubCode = 'RESUB' . $myEvidence->id . 'U' . auth()->id();
                            $bankAccount = config('services.sepay.bank_account');
                            $bankName = config('services.sepay.bank_name');
                            $resubQr = $bankAccount
                                ? 'https://qr.sepay.vn/img?' . http_build_query(['acc' => $bankAccount, 'bank' => $bankName, 'amount' => 34000, 'des' => $resubCode, 'template' => 'compact'])
                                : null;
                        @endphp
                        <div x-data="{ showQr: false }">
                            <button @click="showQr = !showQr" class="btn btn-danger" style="font-size:0.8rem; padding:0.35rem 0.75rem; margin-bottom:0.5rem;">
                                Nộp 34.000đ để làm lại
                            </button>
                            <div x-show="showQr" x-transition style="background:#FEF2F2; border:1px solid #FECACA; border-radius:0.375rem; padding:0.75rem;">
                                @if($resubQr)
                                <div class="text-center" style="margin-bottom:0.5rem;">
                                    <img src="{{ $resubQr }}" alt="QR thanh toán" style="max-width:200px; margin:0 auto; border-radius:0.5rem;">
                                </div>
                                @endif
                                <p style="font-size:0.75rem; color:#636E72;">Nội dung CK: <strong style="color:#FF6B6B;">{{ $resubCode }}</strong></p>
                                <p style="font-size:0.65rem; color:#636E72; margin-top:0.25rem;">Hệ thống tự mở sau khi nhận được tiền (1-3 phút).</p>
                            </div>
                        </div>
                        @else
                        <textarea wire:model="taskEvidence.{{ $task->id }}" class="input" rows="2" placeholder="Paste link bằng chứng mới..." style="font-size:0.8rem;"></textarea>
                        <button wire:click="resubmitTask({{ $task->id }})" class="btn btn-primary mt-2" style="font-size:0.8rem; padding:0.3rem 0.875rem;">
                            Nộp lại
                        </button>
                        @endif
                    </div>
                    @endif
                    @endif
                    @elseif($isUnlocked)
                    <div style="background:#FFFBEB; border:1px solid #FDE68A; border-radius:0.5rem; padding:0.75rem;">
                        <p style="font-size:0.75rem; font-weight:600; color:#92400E; margin-bottom:0.25rem;">
                            ▣ Bằng chứng hoàn thành{{ $task->evidence_label ? ': ' . $task->evidence_label : '' }}
                        </p>
                        <p style="font-size:0.7rem; color:#636E72; margin-bottom:0.5rem;">Upload ảnh lên Google Drive hoặc Imgur, sau đó paste link vào đây.</p>
                        <textarea wire:model="taskEvidence.{{ $task->id }}" class="input" rows="2" placeholder="Paste link Google Drive / Imgur + mô tả ngắn..." style="font-size:0.8rem;"></textarea>
                        <button wire:click="completeTask({{ $task->id }})" class="btn btn-primary mt-2" style="font-size:0.8rem; padding:0.3rem 0.875rem;">
                            Nộp bài & Hoàn thành
                        </button>
                    </div>
                    @endif
                </div>
            </div>
            {{-- Admin note: shown only after task completed & approved --}}
            @if($task->admin_note && $isCompleted && ($myCompletions[$task->id]->status ?? '') === 'approved')
            <div style="background:#FFFBEB; border:1px solid #FDE68A; border-radius:0.5rem; padding:0.75rem; margin:0.5rem 0;">
                <p style="font-size:0.7rem; font-weight:700; color:#92400E; margin-bottom:0.25rem;">Lời nhắn từ Host Challenge</p>
                <div style="font-size:0.8rem; color:#2E2E2E; line-height:1.6; white-space:pre-line;">{{ $task->admin_note }}</div>
            </div>
            @endif
            @endforeach
        </div>
    </div>
    @elseif($tasks->count() > 0 && !$isApproved)
    {{-- Locked: non-member can't see content --}}
    <div class="card mb-4 text-center" style="padding:2rem 1rem;">
        <p style="font-size:2rem; margin-bottom:0.5rem;">▪</p>
        <p style="font-size:0.9rem; font-weight:700; color:#1A1A1A; margin-bottom:0.25rem;">{{ $tasks->count() }} nhiệm vụ trong {{ $expedition->required_days }} ngày</p>
        <p style="font-size:0.8rem; color:#636E72;">Đăng ký và được duyệt để mở khóa nội dung bài tập</p>
    </div>
    @endif

    {{-- Video feedback moved to header card above --}}

    {{-- Admin: Video Feedback Review --}}
    @can('admin')
    @php
        $pendingVideos = $approvedMembers->filter(fn($m) => $m->video_feedback_status === 'pending');
    @endphp
    @if($pendingVideos->count() > 0)
    <div class="card mb-4" style="border-left:3px solid #D97706;">
        <h2 style="font-size:0.9rem; font-weight:700; color:#92400E; margin-bottom:0.75rem;">▶ Video Feedback chờ duyệt ({{ $pendingVideos->count() }})</h2>
        @foreach($pendingVideos as $vm)
        <div class="py-2 {{ !$loop->last ? 'border-b' : '' }}" style="{{ !$loop->last ? 'border-color:#E8E4DE;' : '' }}">
            <div class="flex items-center gap-2 mb-1">
                <img src="{{ $vm->user->avatar_url }}" class="avatar w-6 h-6" alt="">
                <span style="font-size:0.8rem; font-weight:600;">{{ $vm->user->name }}</span>
                <span style="font-size:0.65rem; color:#636E72;">{{ $vm->video_feedback_at ? \Carbon\Carbon::parse($vm->video_feedback_at)->timezone('Asia/Ho_Chi_Minh')->diffForHumans() : '' }}</span>
            </div>
            <a href="{{ $vm->video_feedback_url }}" target="_blank" rel="noopener" style="font-size:0.75rem; color:#FF6B6B; text-decoration:underline; display:block; margin-bottom:0.375rem;">{{ Str::limit($vm->video_feedback_url, 60) }}</a>
            <div x-data="{ showReject: false, note: '' }" class="flex flex-wrap gap-1">
                <button wire:click="approveVideoFeedback({{ $vm->id }})" class="btn btn-primary" style="font-size:0.7rem; padding:0.2rem 0.5rem;">✓ Duyệt</button>
                <button @click="showReject = !showReject" class="btn btn-ghost" style="font-size:0.7rem; padding:0.2rem 0.5rem; color:#991B1B;">✗ Từ chối</button>
                <div x-show="showReject" x-transition style="width:100%; margin-top:0.25rem;">
                    <input x-model="note" type="text" class="input" placeholder="Lý do..." style="font-size:0.75rem; margin-bottom:0.25rem;">
                    <button @click="$wire.rejectVideoFeedback({{ $vm->id }}, note); showReject = false" class="btn btn-danger" style="font-size:0.7rem; padding:0.2rem 0.5rem;">Xác nhận từ chối</button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
    @endcan

    {{-- Admin: Member Report --}}
    @can('admin')
    @if($memberReport->count() > 0)
    <div class="card mb-4" style="border-left:3px solid #FF6B6B;">
        <div class="flex items-center justify-between mb-3">
            <h2 style="font-size:0.9rem; font-weight:700; color:#E85555;">▥ Báo cáo thành viên ({{ $reportTotal }})</h2>
        </div>
        <div style="overflow-x:auto;">
            <table style="width:100%; font-size:0.8rem; border-collapse:collapse;">
                <thead>
                    <tr style="border-bottom:2px solid #E1E1E1;">
                        <th style="text-align:left; padding:0.5rem 0.375rem; color:#636E72; font-size:0.7rem;">Thành viên</th>
                        <th style="text-align:center; padding:0.5rem 0.375rem; color:#636E72; font-size:0.7rem;">Ngày</th>
                        <th style="text-align:center; padding:0.5rem 0.375rem; color:#636E72; font-size:0.7rem;">Hoàn thành</th>
                        <th style="text-align:center; padding:0.5rem 0.375rem; color:#636E72; font-size:0.7rem;">Từ chối</th>
                        <th style="text-align:center; padding:0.5rem 0.375rem; color:#636E72; font-size:0.7rem;">Trễ</th>
                        <th style="text-align:center; padding:0.5rem 0.375rem; color:#636E72; font-size:0.7rem;">Miss</th>
                        <th style="text-align:center; padding:0.5rem 0.375rem; color:#636E72; font-size:0.7rem;">Tiến độ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($memberReport as $r)
                    <tr style="border-bottom:1px solid #E1E1E1;">
                        <td style="padding:0.5rem 0.375rem;">
                            <div class="flex items-center gap-2">
                                <img src="{{ $r->member->user->avatar_url }}" class="avatar w-6 h-6" alt="">
                                <a href="{{ route('profile', $r->member->user->username ?? $r->member->user->id) }}" style="font-weight:600; color:#1A1A1A;">{{ $r->member->user->name }}</a>
                            </div>
                        </td>
                        <td style="text-align:center; padding:0.5rem 0.375rem; color:#636E72;">{{ $r->current_day }}/{{ $r->total }}</td>
                        <td style="text-align:center; padding:0.5rem 0.375rem; color:#059669; font-weight:600;">{{ $r->completed }}</td>
                        <td style="text-align:center; padding:0.5rem 0.375rem; {{ $r->rejected > 0 ? 'color:#DC2626; font-weight:600;' : 'color:#636E72;' }}">{{ $r->rejected }}</td>
                        <td style="text-align:center; padding:0.5rem 0.375rem; {{ $r->late > 0 ? 'color:#D97706; font-weight:600;' : 'color:#636E72;' }}">{{ $r->late }}</td>
                        <td style="text-align:center; padding:0.5rem 0.375rem; {{ $r->missed > 0 ? 'color:#DC2626; font-weight:700;' : 'color:#636E72;' }}">{{ $r->missed }}</td>
                        <td style="padding:0.5rem 0.375rem;">
                            <div style="display:flex; align-items:center; gap:0.375rem;">
                                <div style="flex:1; height:6px; background:#EEECE9; border-radius:3px; overflow:hidden;">
                                    <div style="height:100%; border-radius:3px; width:{{ $r->pct }}%; {{ $r->missed > 0 ? 'background:#DC2626;' : 'background:#059669;' }}"></div>
                                </div>
                                <span style="font-size:0.65rem; color:#636E72; white-space:nowrap;">{{ $r->pct }}%</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($reportLastPage > 1)
        <div class="flex items-center justify-between mt-3" style="font-size:0.75rem;">
            <span style="color:#636E72;">Trang {{ $reportPage }}/{{ $reportLastPage }}</span>
            <div class="flex gap-1">
                <button wire:click="$set('reportPage', {{ max(1, $reportPage - 1) }})" class="btn btn-ghost" style="font-size:0.65rem; padding:0.2rem 0.5rem;" @if($reportPage <= 1) disabled style="opacity:0.4; font-size:0.65rem; padding:0.2rem 0.5rem;" @endif>← Trước</button>
                <button wire:click="$set('reportPage', {{ min($reportLastPage, $reportPage + 1) }})" class="btn btn-ghost" style="font-size:0.65rem; padding:0.2rem 0.5rem;" @if($reportPage >= $reportLastPage) disabled style="opacity:0.4; font-size:0.65rem; padding:0.2rem 0.5rem;" @endif>Sau →</button>
            </div>
        </div>
        @endif
    </div>
    @endif
    @endcan

    {{-- Submission history --}}
    @if($submissions && $submissions->count() > 0)
    <div class="card">
        <div class="flex items-center justify-between mb-3">
            <h2 style="font-size:0.9rem; font-weight:700; color:#1A1A1A;">Lịch sử nộp bài ({{ $submissions->total() }})</h2>
            @can('admin')
            @if($submissions->where('review_status', 'pending')->count() > 0)
            <button wire:click="approveAllPending" wire:confirm="Duyệt tất cả bài đang chờ?" class="btn btn-primary" style="font-size:0.7rem; padding:0.25rem 0.625rem;">
                ✓ Duyệt tất cả ({{ $submissions->where('review_status', 'pending')->count() }})
            </button>
            @endif
            @endcan
        </div>
        @foreach($submissions as $sub)
        <div class="py-3 {{ !$loop->last ? 'border-b' : '' }}" style="{{ !$loop->last ? 'border-color:#E1E1E1;' : '' }}">
            <div class="flex items-center gap-2 mb-1">
                @php $avatarUrl = $sub->avatar ? asset('storage/' . $sub->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($sub->name) . '&background=7C3AED&color=fff&size=64'; @endphp
                <img src="{{ $avatarUrl }}" class="avatar w-6 h-6" alt="">
                <a href="{{ route('profile', $sub->username ?? $sub->user_id) }}" style="font-size:0.8rem; font-weight:600; color:#1A1A1A;">{{ $sub->name }}</a>
                <span style="font-size:0.7rem; color:#FF6B6B; background:#E8F5E9; padding:0.1rem 0.375rem; border-radius:999px;">Ngày {{ $sub->day_number }}</span>
                @if($sub->is_late)
                <span style="font-size:0.6rem; color:#D97706; background:#FEF3C7; padding:0.1rem 0.375rem; border-radius:999px;">Trễ</span>
                @endif
                @if($sub->review_status === 'approved')
                <span style="font-size:0.6rem; color:#059669; background:#D1FAE5; padding:0.1rem 0.375rem; border-radius:999px;">✓ Đã duyệt</span>
                @elseif($sub->review_status === 'rejected')
                <span style="font-size:0.6rem; color:#DC2626; background:#FEE2E2; padding:0.1rem 0.375rem; border-radius:999px;">✗ Từ chối</span>
                @else
                <span style="font-size:0.6rem; color:#92400E; background:#FEF3C7; padding:0.1rem 0.375rem; border-radius:999px;">Chờ duyệt</span>
                @endif
                <span style="font-size:0.65rem; color:#636E72;">{{ \Carbon\Carbon::parse($sub->created_at)->diffForHumans() }}</span>
            </div>
            <p style="font-size:0.775rem; font-weight:500; color:#1A1A1A; padding-left:2rem;">{{ $sub->task_title }}</p>
            @if($sub->evidence)
            <p style="font-size:0.75rem; color:#636E72; padding-left:2rem; margin-top:0.25rem; line-height:1.4; overflow-wrap:break-word;">{!! preg_replace('#(https?://[^\s<]+)#i', '<a href="$1" target="_blank" rel="noopener" style="color:#FF6B6B; text-decoration:underline;">$1</a>', e($sub->evidence)) !!}</p>
            @endif
            @if($sub->review_note)
            <p style="font-size:0.7rem; color:#DC2626; padding-left:2rem; margin-top:0.25rem;">💬 {{ $sub->review_note }}</p>
            @endif

            {{-- Admin review buttons --}}
            @can('admin')
            @if($sub->review_status === 'pending')
            <div x-data="{ showReject: false, note: '' }" style="padding-left:2rem; margin-top:0.375rem;">
                <div class="flex gap-1">
                    <button wire:click="approveSubmission({{ $sub->completion_id }})" class="btn btn-primary" style="font-size:0.7rem; padding:0.2rem 0.5rem;">✓ Duyệt</button>
                    <button @click="showReject = !showReject" class="btn btn-ghost" style="font-size:0.7rem; padding:0.2rem 0.5rem; color:#991B1B;">✗ Từ chối</button>
                </div>
                <div x-show="showReject" x-transition style="margin-top:0.375rem;">
                    <input x-model="note" type="text" class="input" placeholder="Lý do từ chối..." style="font-size:0.75rem; margin-bottom:0.25rem;">
                    <button @click="$wire.rejectSubmission({{ $sub->completion_id }}, note); showReject = false" class="btn btn-danger" style="font-size:0.7rem; padding:0.2rem 0.5rem;">Xác nhận từ chối</button>
                </div>
            </div>
            @endif
            @endcan
        </div>
        @endforeach
        @if($submissions->lastPage() > 1)
        <div class="flex items-center justify-between mt-3" style="font-size:0.75rem;">
            <span style="color:#636E72;">Trang {{ $submissions->currentPage() }}/{{ $submissions->lastPage() }}</span>
            <div class="flex gap-1">
                <button wire:click="$set('submissionPage', {{ max(1, $submissions->currentPage() - 1) }})" class="btn btn-ghost" style="font-size:0.65rem; padding:0.2rem 0.5rem;" @if($submissions->onFirstPage()) disabled style="opacity:0.4; font-size:0.65rem; padding:0.2rem 0.5rem;" @endif>← Trước</button>
                <button wire:click="$set('submissionPage', {{ min($submissions->lastPage(), $submissions->currentPage() + 1) }})" class="btn btn-ghost" style="font-size:0.65rem; padding:0.2rem 0.5rem;" @if(!$submissions->hasMorePages()) disabled style="opacity:0.4; font-size:0.65rem; padding:0.2rem 0.5rem;" @endif>Sau →</button>
            </div>
        </div>
        @endif
    </div>
    @endif
</div>
