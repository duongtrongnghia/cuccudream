<div>
    <div class="card">

        {{-- ── Step 1: Account type selection ─────────────────────────── --}}
        @if ($showTypeSelection)
            <h1 style="font-size:1.4rem; font-weight:800; color:#2D2926; margin-bottom:0.25rem; text-align:center;">
                Bạn muốn tạo tài khoản gì?
            </h1>
            <p style="color:#8B7E74; font-size:0.875rem; margin-bottom:1.5rem; text-align:center;">
                Chọn loại tài khoản phù hợp với bạn
            </p>

            @error('accountType')
                <p style="color:#991B1B; font-size:0.8rem; margin-bottom:0.75rem; text-align:center;">{{ $message }}</p>
            @enderror

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.875rem; margin-bottom:1.25rem;">

                {{-- Ba Mẹ card --}}
                <button type="button"
                        wire:click="selectType('parent')"
                        style="
                            background: {{ $accountType === 'parent' ? '#FDF0EB' : '#FFFCF7' }};
                            border: 2px solid {{ $accountType === 'parent' ? '#D4896E' : '#E0D5C5' }};
                            border-radius: 0.75rem;
                            padding: 1.25rem 0.875rem;
                            text-align: center;
                            cursor: pointer;
                            transition: border-color 0.15s, background 0.15s;
                            width: 100%;
                        ">
                    <div style="font-size:2rem; margin-bottom:0.5rem;">🌿</div>
                    <div style="font-size:1rem; font-weight:800; color:#2D2926; margin-bottom:0.25rem;">Ba Mẹ</div>
                    <div style="font-size:0.75rem; color:#8B7E74; font-weight:500;">Tạo tài khoản phụ huynh</div>
                </button>

                {{-- Bé card --}}
                <button type="button"
                        wire:click="selectType('kid')"
                        style="
                            background: {{ $accountType === 'kid' ? '#EDF0EC' : '#FFFCF7' }};
                            border: 2px solid {{ $accountType === 'kid' ? '#7B8B6F' : '#E0D5C5' }};
                            border-radius: 0.75rem;
                            padding: 1.25rem 0.875rem;
                            text-align: center;
                            cursor: pointer;
                            transition: border-color 0.15s, background 0.15s;
                            width: 100%;
                        ">
                    <div style="font-size:2rem; margin-bottom:0.5rem;">🎨</div>
                    <div style="font-size:1rem; font-weight:800; color:#2D2926; margin-bottom:0.25rem;">Bé</div>
                    <div style="font-size:0.75rem; color:#8B7E74; font-weight:500;">Tạo tài khoản cho bé</div>
                </button>
            </div>

            <button type="button"
                    wire:click="proceedToForm"
                    class="btn btn-gold w-full justify-center"
                    style="font-size:1rem; padding:0.75rem 1rem; opacity:{{ $accountType ? '1' : '0.5' }};">
                Tiếp tục →
            </button>

            <p style="text-align:center; margin-top:1rem; font-size:0.875rem; color:#8B7E74;">
                Đã có tài khoản?
                <a href="{{ route('login') }}" style="color:#D4896E; font-weight:700;">Đăng nhập</a>
            </p>
        @else
            {{-- ── Step 2: Registration form ───────────────────────────── --}}

            {{-- Back to type selection --}}
            <button type="button"
                    wire:click="$set('showTypeSelection', true)"
                    style="display:inline-flex; align-items:center; gap:0.375rem; font-size:0.8rem; color:#8B7E74; font-weight:600; background:none; border:none; cursor:pointer; margin-bottom:1rem; padding:0;">
                ← Quay lại
            </button>

            {{-- Header --}}
            <div style="display:flex; align-items:center; gap:0.625rem; margin-bottom:0.25rem;">
                <span style="font-size:1.5rem;">{{ $accountType === 'kid' ? '🎨' : '🌿' }}</span>
                <h1 style="font-size:1.375rem; font-weight:800; color:#2D2926;">
                    {{ $accountType === 'kid' ? 'Tài khoản Bé' : 'Tài khoản Ba Mẹ' }}
                </h1>
            </div>
            @if ($accountType === 'parent')
                <p style="color:#8B7E74; font-size:0.875rem; margin-bottom:1.5rem;">3 ngày dùng thử miễn phí 🎁</p>
            @else
                <p style="color:#8B7E74; font-size:0.875rem; margin-bottom:1.5rem;">Tạo tài khoản cho bé tham gia học cùng!</p>
            @endif

            <form wire:submit="register" class="flex flex-col gap-4">

                {{-- Name --}}
                <div>
                    <label style="display:block; font-size:0.8rem; font-weight:700; color:#2D2926; margin-bottom:0.375rem;">
                        {{ $accountType === 'kid' ? 'Tên bé' : 'Họ và tên' }}
                    </label>
                    <input wire:model="name" type="text" class="input"
                           placeholder="{{ $accountType === 'kid' ? 'Ví dụ: Bé Hoa' : 'Nguyễn Văn A' }}"
                           autofocus>
                    @error('name') <p style="color:#991B1B; font-size:0.75rem; margin-top:0.25rem;">{{ $message }}</p> @enderror
                </div>

                {{-- Email (parent only) --}}
                @if ($accountType === 'parent')
                    <div>
                        <label style="display:block; font-size:0.8rem; font-weight:700; color:#2D2926; margin-bottom:0.375rem;">Email</label>
                        <input wire:model="email" type="email" class="input" placeholder="ban@email.com">
                        @error('email') <p style="color:#991B1B; font-size:0.75rem; margin-top:0.25rem;">{{ $message }}</p> @enderror
                    </div>
                @endif

                {{-- Password --}}
                <div>
                    <label style="display:block; font-size:0.8rem; font-weight:700; color:#2D2926; margin-bottom:0.375rem;">Mật khẩu</label>
                    <input wire:model="password" type="password" class="input"
                           placeholder="{{ $accountType === 'kid' ? 'Tối thiểu 6 ký tự' : 'Tối thiểu 8 ký tự' }}">
                    @error('password') <p style="color:#991B1B; font-size:0.75rem; margin-top:0.25rem;">{{ $message }}</p> @enderror
                </div>

                {{-- Password confirmation (parent only) --}}
                @if ($accountType === 'parent')
                    <div>
                        <label style="display:block; font-size:0.8rem; font-weight:700; color:#2D2926; margin-bottom:0.375rem;">Xác nhận mật khẩu</label>
                        <input wire:model="password_confirmation" type="password" class="input" placeholder="Nhập lại mật khẩu">
                    </div>
                @endif

                <button type="submit" class="btn btn-gold w-full justify-center"
                        style="font-size:1rem; padding:0.75rem 1rem;"
                        wire:loading.attr="disabled">
                    <span wire:loading.remove>
                        {{ $accountType === 'kid' ? 'Tạo tài khoản bé 🌱' : 'Bắt đầu dùng thử miễn phí' }}
                    </span>
                    <span wire:loading>Đang tạo tài khoản...</span>
                </button>
            </form>

            <p style="text-align:center; margin-top:1.25rem; font-size:0.75rem; color:#8B7E74;">
                Khi đăng ký, bạn đồng ý với Điều khoản sử dụng của Cúc Cu Dream™
            </p>

            <p style="text-align:center; margin-top:0.75rem; font-size:0.875rem; color:#8B7E74;">
                Đã có tài khoản?
                <a href="{{ route('login') }}" style="color:#D4896E; font-weight:700;">Đăng nhập</a>
            </p>
        @endif
    </div>
</div>
