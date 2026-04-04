<div>
    <div style="max-width:480px; margin:0 auto; padding:1.5rem 1rem;">

        {{-- Back link --}}
        <a href="{{ route('family') }}" wire:navigate
           style="display:inline-flex; align-items:center; gap:0.375rem; font-size:0.875rem; color:#8B7E74; font-weight:600; margin-bottom:1.25rem; text-decoration:none;">
            ← Quay lại
        </a>

        <div class="card">
            {{-- Header --}}
            <div style="margin-bottom:1.5rem;">
                <div style="font-size:2rem; margin-bottom:0.5rem;">🎨</div>
                <h1 style="font-size:1.375rem; font-weight:800; color:#2D2926; margin-bottom:0.25rem;">
                    Tạo tài khoản cho bé
                </h1>
                <p style="font-size:0.875rem; color:#8B7E74;">
                    Bé sẽ dùng tên đăng nhập và mật khẩu để đăng nhập
                </p>
            </div>

            <form wire:submit="createKid" class="flex flex-col gap-4">

                {{-- Tên bé --}}
                <div>
                    <label style="display:block; font-size:0.8rem; font-weight:700; color:#2D2926; margin-bottom:0.375rem;">
                        Tên bé
                    </label>
                    <input wire:model.live="name"
                           type="text"
                           class="input"
                           placeholder="Ví dụ: Bé Hoa"
                           autofocus>
                    @error('name')
                        <p style="color:#991B1B; font-size:0.75rem; margin-top:0.25rem;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tên đăng nhập --}}
                <div>
                    <label style="display:block; font-size:0.8rem; font-weight:700; color:#2D2926; margin-bottom:0.375rem;">
                        Tên đăng nhập
                        <span style="font-weight:500; color:#8B7E74;">(tự động tạo, có thể sửa)</span>
                    </label>
                    <input wire:model="username"
                           type="text"
                           class="input"
                           placeholder="be.hoa">
                    @error('username')
                        <p style="color:#991B1B; font-size:0.75rem; margin-top:0.25rem;">{{ $message }}</p>
                    @enderror
                    <p style="font-size:0.72rem; color:#8B7E74; margin-top:0.25rem;">
                        Chỉ dùng chữ thường, số, dấu chấm (.) và gạch dưới (_)
                    </p>
                </div>

                {{-- Mật khẩu --}}
                <div>
                    <label style="display:block; font-size:0.8rem; font-weight:700; color:#2D2926; margin-bottom:0.375rem;">
                        Mật khẩu
                    </label>
                    <input wire:model="password"
                           type="password"
                           class="input"
                           placeholder="Tối thiểu 6 ký tự">
                    @error('password')
                        <p style="color:#991B1B; font-size:0.75rem; margin-top:0.25rem;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Info note --}}
                <div style="background:#F5EEDA; border:1px solid #E0CFA0; border-radius:0.625rem; padding:0.75rem; display:flex; gap:0.625rem; align-items:flex-start;">
                    <span style="font-size:1rem; flex-shrink:0;">💡</span>
                    <p style="font-size:0.8rem; color:#5C4A1E; font-weight:600; line-height:1.5;">
                        Bé sẽ đăng nhập bằng <strong>tên đăng nhập</strong> và <strong>mật khẩu</strong> này. Ba Mẹ hãy ghi lại để không quên nhé!
                    </p>
                </div>

                {{-- Submit --}}
                <button type="submit"
                        class="btn btn-gold w-full justify-center"
                        style="font-size:1rem; padding:0.75rem 1rem;"
                        wire:loading.attr="disabled">
                    <span wire:loading.remove>Tạo tài khoản bé 🌱</span>
                    <span wire:loading>Đang tạo...</span>
                </button>
            </form>
        </div>
    </div>
</div>
