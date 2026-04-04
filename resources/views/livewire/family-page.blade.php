<div>
    <div style="max-width:640px; margin:0 auto; padding:1.5rem 1rem;">

        {{-- Flash message --}}
        @if (session()->has('message'))
            <div style="background:#E5EBE0; border:1px solid #7B8B6F; color:#3D4A35; border-radius:0.75rem; padding:0.75rem 1rem; margin-bottom:1rem; font-weight:600;">
                {{ session('message') }}
            </div>
        @endif

        {{-- Header --}}
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.25rem;">
            <div>
                <h1 style="font-size:1.5rem; font-weight:800; color:#2D2926;">Gia đình của tôi 🌿</h1>
                <p style="font-size:0.875rem; color:#8B7E74; margin-top:0.125rem;">
                    {{ $children->count() }}/5 bé đã tạo tài khoản
                </p>
            </div>
            @if ($canAddMore)
                <a href="{{ route('family.create-kid') }}" wire:navigate class="btn btn-gold" style="font-size:0.9rem;">
                    + Thêm bé
                </a>
            @else
                <span style="font-size:0.8rem; color:#8B7E74; background:#EDE5D8; border-radius:0.5rem; padding:0.4rem 0.75rem; font-weight:600;">
                    Đã đạt tối đa 5 bé
                </span>
            @endif
        </div>

        {{-- Kids list --}}
        @if ($children->isEmpty())
            <div class="card" style="text-align:center; padding:3rem 1.5rem;">
                <div style="font-size:3rem; margin-bottom:0.75rem;">🎨</div>
                <p style="font-size:1.05rem; font-weight:700; color:#2D2926; margin-bottom:0.375rem;">
                    Chưa có bé nào
                </p>
                <p style="color:#8B7E74; font-size:0.9rem; margin-bottom:1.5rem;">
                    Thêm bé để cùng học và khám phá!
                </p>
                <a href="{{ route('family.create-kid') }}" wire:navigate class="btn btn-gold" style="display:inline-flex;">
                    + Thêm bé đầu tiên
                </a>
            </div>
        @else
            <div style="display:flex; flex-direction:column; gap:0.875rem;">
                @foreach ($children as $child)
                    <div class="card" style="display:flex; align-items:center; gap:1rem;">
                        {{-- Avatar --}}
                        <img src="{{ $child->avatar_url }}" alt="{{ $child->name }}"
                             class="avatar"
                             style="width:52px; height:52px; border-radius:50%; object-fit:cover; border:2px solid #E0D5C5; flex-shrink:0;">

                        {{-- Info --}}
                        <div style="flex:1; min-width:0;">
                            <div style="display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap;">
                                <span style="font-size:1rem; font-weight:700; color:#2D2926;">{{ $child->name }}</span>
                                <span class="level-badge" style="font-size:0.7rem; padding:0.1rem 0.45rem;">Lv.{{ $child->level }}</span>
                            </div>
                            <p style="font-size:0.8rem; color:#7B8B6F; font-weight:600; margin-top:0.1rem;">
                                {{ $child->job_stage }}
                            </p>
                            <p style="font-size:0.75rem; color:#8B7E74; margin-top:0.1rem;">
                                @if ($child->last_active_at)
                                    Hoạt động {{ $child->last_active_at->diffForHumans() }}
                                @else
                                    Chưa đăng nhập lần nào
                                @endif
                            </p>
                        </div>

                        {{-- XP pill --}}
                        <div style="text-align:right; flex-shrink:0;">
                            <span style="font-size:0.75rem; font-weight:700; color:#C9A84C; background:#F5EEDA; border-radius:9999px; padding:0.2rem 0.6rem; display:inline-block;">
                                {{ number_format($child->xp) }} XP
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Count indicator bar --}}
        @if ($children->isNotEmpty())
            <div style="margin-top:1.25rem; display:flex; align-items:center; gap:0.5rem;">
                @for ($i = 0; $i < 5; $i++)
                    <div style="flex:1; height:6px; border-radius:9999px; background:{{ $i < $children->count() ? '#D4896E' : '#E0D5C5' }};"></div>
                @endfor
                <span style="font-size:0.75rem; color:#8B7E74; font-weight:600; white-space:nowrap;">
                    {{ $children->count() }}/5
                </span>
            </div>
        @endif
    </div>
</div>
