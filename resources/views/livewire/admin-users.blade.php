<div>
    <h1 style="font-size:1.25rem; font-weight:800; color:#1A1A1A; margin-bottom:1rem;">■ Quản lý người dùng</h1>

    <input wire:model.live.debounce.300ms="search" type="search" class="input mb-4" placeholder="Tìm theo tên, email, username..." style="max-width:400px;">

    <div class="card">
        <table style="width:100%; font-size:0.8rem;">
            <thead>
                <tr style="border-bottom:1px solid #E1E1E1; text-align:left;">
                    <th style="padding:0.5rem; color:#636E72; font-weight:600;">Người dùng</th>
                    <th style="padding:0.5rem; color:#636E72; font-weight:600;">Level</th>
                    <th style="padding:0.5rem; color:#636E72; font-weight:600;">EXP</th>
                    <th style="padding:0.5rem; color:#636E72; font-weight:600;">Posts</th>
                    <th style="padding:0.5rem; color:#636E72; font-weight:600;">Status</th>
                    <th style="padding:0.5rem; color:#636E72; font-weight:600;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr style="border-bottom:1px solid #E1E1E1;">
                    <td style="padding:0.5rem;">
                        <div class="flex items-center gap-2">
                            <img src="{{ $user->avatar_url }}" class="avatar w-7 h-7" alt="">
                            <div>
                                <div class="flex items-center gap-1">
                                    <p style="font-weight:600; color:#1A1A1A;">{{ $user->name }}</p>
                                </div>
                                <p style="font-size:0.7rem; color:#636E72;">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td style="padding:0.5rem;"><span class="level-badge">Lv.{{ $user->level }}</span></td>
                    <td style="padding:0.5rem; color:#FF6B6B; font-weight:600;">{{ number_format($user->xp) }}</td>
                    <td style="padding:0.5rem;">{{ $user->posts_count }}</td>
                    <td style="padding:0.5rem;">
                        @if($user->is_admin)<span class="badge" style="background:#FEE2E2; color:#991B1B; font-size:0.6rem;">Admin</span>@endif
                        @if($user->is_moderator)<span class="badge" style="background:#DBEAFE; color:#1E40AF; font-size:0.6rem;">Mod</span>@endif
                        @if($user->membership?->status === 'banned')<span class="badge" style="background:#FEE2E2; color:#991B1B; font-size:0.6rem;">Banned</span>@endif
                    </td>
                    <td style="padding:0.5rem;">
                        <div class="flex gap-1">
                            <button wire:click="toggleAdmin({{ $user->id }})" class="btn btn-ghost" style="font-size:0.65rem; padding:0.2rem 0.4rem;">{{ $user->is_admin ? '- Admin' : '+ Admin' }}</button>
                            <button wire:click="toggleModerator({{ $user->id }})" class="btn btn-ghost" style="font-size:0.65rem; padding:0.2rem 0.4rem;">{{ $user->is_moderator ? '- Mod' : '+ Mod' }}</button>
                            @if($user->membership?->status === 'banned')
                            <button wire:click="unbanUser({{ $user->id }})" class="btn btn-success" style="font-size:0.65rem; padding:0.2rem 0.4rem;">Unban</button>
                            @else
                            <button wire:click="banUser({{ $user->id }})" wire:confirm="Ban user này?" class="btn btn-danger" style="font-size:0.65rem; padding:0.2rem 0.4rem;">Ban</button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $users->links() }}</div>
</div>
