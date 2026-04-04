<div>
    <div class="card">
        <h1 style="font-size:1.5rem; font-weight:700; color:#1A1A1A; margin-bottom:0.25rem;">Đăng nhập</h1>
        <p style="color:#636E72; font-size:0.875rem; margin-bottom:1.5rem;">Chào mừng trở lại 👋</p>

        @if($error)
        <div style="background:#FEE2E2; border:1px solid #FECACA; color:#991B1B; padding:0.75rem; border-radius:0.5rem; font-size:0.875rem; margin-bottom:1rem;">
            {{ $error }}
        </div>
        @endif

        <form wire:submit="authenticate" class="flex flex-col gap-4">
            <div>
                <label style="display:block; font-size:0.8rem; font-weight:600; color:#2D2926; margin-bottom:0.375rem;">Email hoặc Tên đăng nhập</label>
                <input wire:model="login" type="text" class="input" placeholder="email hoặc tên đăng nhập" autofocus>
                @error('login') <p style="color:#991B1B; font-size:0.75rem; margin-top:0.25rem;">{{ $message }}</p> @enderror
            </div>

            <div x-data="{ show: false }">
                <label style="display:block; font-size:0.8rem; font-weight:600; color:#2E2E2E; margin-bottom:0.375rem;">Mật khẩu</label>
                <div style="position:relative;">
                    <input wire:model="password" :type="show ? 'text' : 'password'" class="input" placeholder="••••••••" style="padding-right:2.5rem;">
                    <button type="button" @click="show = !show"
                        style="position:absolute; right:0.625rem; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; color:#636E72; padding:0.25rem; display:flex; align-items:center;"
                        :aria-label="show ? 'Ẩn mật khẩu' : 'Hiện mật khẩu'">
                        <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <svg x-show="show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                        </svg>
                    </button>
                </div>
                @error('password') <p style="color:#991B1B; font-size:0.75rem; margin-top:0.25rem;">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2" style="font-size:0.8rem; color:#636E72; cursor:pointer;">
                    <input wire:model="remember" type="checkbox" style="accent-color:#D4896E;">
                    Nhớ đăng nhập
                </label>
                <span style="font-size:0.8rem; color:#636E72;">Quên mật khẩu? Liên hệ Admin</span>
            </div>

            <button type="submit" class="btn btn-primary w-full justify-center" wire:loading.attr="disabled">
                <span wire:loading.remove>Đăng nhập</span>
                <span wire:loading>Đang xử lý...</span>
            </button>
        </form>

        <p style="text-align:center; margin-top:1.25rem; font-size:0.875rem; color:#636E72;">
            Chưa có tài khoản?
            <a href="{{ route('register') }}" style="color:#D4896E; font-weight:600;">Tham gia ngay</a>
        </p>
    </div>
</div>
