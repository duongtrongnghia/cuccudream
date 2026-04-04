<div>
    <div class="flex items-center justify-between mb-4">
        <h1 style="font-size:1.25rem; font-weight:800; color:#1A1A1A;">Hỏi đáp Q&amp;A</h1>
        @auth
        <button wire:click="$toggle('showAsk')" class="btn btn-primary" style="font-size:0.875rem;">
            + Đặt câu hỏi
        </button>
        @endauth
    </div>

    @if($showAsk)
    <div class="card mb-4">
        <h3 style="font-size:0.9rem; font-weight:700; color:#1A1A1A; margin-bottom:1rem;">Câu hỏi của bạn</h3>
        <div class="flex flex-col gap-3">
            <div>
                <label style="display:block; font-size:0.8rem; font-weight:600; color:#2E2E2E; margin-bottom:0.375rem;">Tiêu đề *</label>
                <input wire:model="title" class="input" placeholder="Câu hỏi ngắn gọn của bạn...">
                @error('title') <p style="color:#991B1B; font-size:0.75rem; margin-top:0.25rem;">{{ $message }}</p> @enderror
            </div>
            <div>
                <label style="display:block; font-size:0.8rem; font-weight:600; color:#2E2E2E; margin-bottom:0.375rem;">Chi tiết (tùy chọn)</label>
                <textarea wire:model="body" class="input" rows="3" placeholder="Mô tả thêm context..."
                    x-data x-init="$el.style.height = $el.scrollHeight + 'px'"
                    @input="$el.style.height = 'auto'; $el.style.height = $el.scrollHeight + 'px'"
                    style="overflow:hidden; resize:none;"></textarea>
            </div>
            <div class="flex flex-wrap gap-3 items-center">
                <label class="flex items-center gap-1.5" style="font-size:0.8rem; color:#2E2E2E; cursor:pointer;">
                    <input wire:model="isAnonymous" type="checkbox" style="accent-color:#FF6B6B;">
                    Ẩn danh
                </label>
                <div class="flex gap-2 ml-auto">
                    <button wire:click="$set('showAsk',false)" class="btn btn-ghost" style="font-size:0.8rem;">Hủy</button>
                    <button wire:click="submitQuestion" class="btn btn-primary" style="font-size:0.875rem;">Đăng câu hỏi</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="tab-nav">
        <button wire:click="setFilter('all')" class="tab-item {{ $filter === 'all' ? 'active' : '' }}">Tất cả</button>
        <button wire:click="setFilter('unanswered')" class="tab-item {{ $filter === 'unanswered' ? 'active' : '' }}">Chưa trả lời</button>
        <button wire:click="setFilter('answered')" class="tab-item {{ $filter === 'answered' ? 'active' : '' }}">Đã trả lời</button>
        @auth <button wire:click="setFilter('mine')" class="tab-item {{ $filter === 'mine' ? 'active' : '' }}">Của tôi</button> @endauth
    </div>

    <div class="flex flex-col gap-3">
        @forelse($questions as $q)
        <div class="card" style="padding:0; overflow:hidden;">
            {{-- Question header --}}
            <button wire:click="toggleQuestion({{ $q->id }})" class="w-full text-left" style="padding:1rem; cursor:pointer;">
                <div class="flex items-start gap-3">
                    @if($q->is_anonymous)
                    <div style="width:36px; height:36px; border-radius:50%; background:#EEECE9; display:flex; align-items:center; justify-content:center; font-size:0.875rem; flex-shrink:0;">❓</div>
                    @else
                    <img src="{{ $q->user->avatar_url }}" class="avatar w-9 h-9 shrink-0" alt="">
                    @endif
                    <div style="flex:1; min-width:0;">
                        <h3 style="font-size:0.9rem; font-weight:600; color:#1A1A1A; line-height:1.4;">{{ $q->title }}</h3>
                        <div class="flex flex-wrap items-center gap-2 mt-1">
                            <span style="font-size:0.75rem; color:#636E72;">{{ $q->is_anonymous ? 'Ẩn danh' : $q->user->name }}</span>
                            <span style="font-size:0.7rem; color:#636E72;">{{ $q->created_at->diffForHumans() }}</span>
                        </div>
                        @if($q->body && $openQuestionId !== $q->id)
                        <p style="font-size:0.8rem; color:#636E72; margin-top:0.5rem; line-height:1.5;">{{ Str::limit($q->body, 120) }}</p>
                        @endif
                    </div>
                    <div class="shrink-0 text-right">
                        @if($q->status === 'answered')
                        <span class="badge" style="background:#D1FAE5; color:#065F46; font-size:0.65rem;">✓ Đã trả lời</span>
                        @else
                        <span class="badge" style="background:#E8F5E9; color:#E85555; font-size:0.65rem;">Chờ trả lời</span>
                        @endif
                        <p style="font-size:0.7rem; color:#636E72; margin-top:0.25rem;">{{ $q->answers_count }} trả lời</p>
                    </div>
                </div>
            </button>

            {{-- Expanded: full body + answers + reply form --}}
            @if($openQuestionId === $q->id)
            <div style="border-top:1px solid #E1E1E1; padding:1rem;">
                @if($q->body)
                <p style="font-size:0.85rem; color:#2E2E2E; line-height:1.6; margin-bottom:1rem; white-space:pre-line;">{{ $q->body }}</p>
                @endif

                {{-- Existing answers --}}
                @foreach($q->answers as $a)
                <div class="flex gap-2 mb-3">
                    <img src="{{ $a->user->avatar_url }}" class="avatar w-8 h-8 shrink-0" alt="">
                    <div style="flex:1; background:#FFF9F0; border-radius:0.5rem; padding:0.625rem 0.875rem;">
                        <div class="flex items-center gap-2 mb-1">
                            <span style="font-weight:600; font-size:0.8rem; color:#1A1A1A;">{{ $a->user->name }}</span>
                            @if($a->is_best)
                            <span class="badge" style="background:#D1FAE5; color:#065F46; font-size:0.6rem;">✓ Trả lời hay nhất</span>
                            @endif
                            <span style="font-size:0.7rem; color:#636E72;">{{ $a->created_at->diffForHumans() }}</span>
                        </div>
                        <p style="color:#2E2E2E; font-size:0.8rem; line-height:1.5; white-space:pre-line;">{{ $a->body }}</p>
                    </div>
                </div>
                @endforeach

                {{-- Reply form --}}
                @auth
                <div class="flex gap-2 mt-3">
                    <img src="{{ auth()->user()->avatar_url }}" class="avatar w-8 h-8 shrink-0" alt="">
                    <div style="flex:1;">
                        <textarea wire:model="answerBody" class="input" rows="2" placeholder="Viết câu trả lời..." style="font-size:0.8rem;"></textarea>
                        @error('answerBody') <p style="color:#991B1B; font-size:0.7rem; margin-top:0.25rem;">{{ $message }}</p> @enderror
                        <div class="flex justify-end mt-1">
                            <button wire:click="submitAnswer" wire:loading.attr="disabled" class="btn btn-primary" style="font-size:0.8rem; padding:0.3rem 0.75rem;">
                                <span wire:loading.remove wire:target="submitAnswer">Trả lời</span>
                                <span wire:loading wire:target="submitAnswer">Đang gửi...</span>
                            </button>
                        </div>
                    </div>
                </div>
                @endauth
            </div>
            @endif
        </div>
        @empty
        <div class="card text-center py-12">
            <p style="font-size:2rem; margin-bottom:0.5rem;">💬</p>
            <p style="color:#636E72;">Chưa có câu hỏi nào</p>
        </div>
        @endforelse
    </div>
    <div class="mt-6">{{ $questions->links() }}</div>
</div>
