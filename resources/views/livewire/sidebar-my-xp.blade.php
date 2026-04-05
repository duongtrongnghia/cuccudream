<div class="card" style="padding:0.875rem;">
    <div class="flex items-center gap-2 mb-3">
        <img src="{{ $user->avatar_url }}" class="avatar w-10 h-10" alt="">
        <div style="flex:1; min-width:0;">
            <p style="font-size:0.9rem; font-weight:800; color:#1A1A1A; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $user->name }}</p>
            <p style="font-size:0.75rem; color:#4A4A4A; font-weight:700;">🎨 {{ $user->job_stage }}</p>
        </div>
    </div>

    {{-- Level progress --}}
    @if($user->level < 10)
    <div class="flex justify-between items-center mb-1">
        <span style="font-size:0.75rem; color:#1A1A1A; font-weight:700;">Lv.{{ $user->level }}</span>
        <span style="font-size:0.75rem; color:#1A1A1A; font-weight:600;">Lv.{{ $user->level + 1 }}</span>
    </div>
    <div class="xp-bar mb-1">
        <div class="xp-bar-fill" style="width:{{ $progress }}%;"></div>
    </div>
    <p style="font-size:0.7rem; color:#4A4A4A; text-align:center; font-weight:600;">Còn {{ number_format($toNext) }} EXP</p>
    @else
    <div style="text-align:center; padding:0.25rem 0;">
        <p style="font-size:0.8rem; font-weight:800; color:#D4896E;">✨ Lv.10 — MAX</p>
    </div>
    @endif

    {{-- Streak --}}
    <div class="flex justify-center mt-2" style="padding-top:0.5rem; border-top:1px solid #E0D5C5;">
        <div class="text-center">
            <p style="font-size:1rem; font-weight:800; color:#D4896E;">🔥 {{ $user->streak }}</p>
            <p style="font-size:0.7rem; color:#4A4A4A; font-weight:600;">Ngày liên tiếp</p>
        </div>
    </div>
</div>
