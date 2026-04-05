<!DOCTYPE html>
<html lang="vi" x-data x-init="
    const f = localStorage.getItem('ccd_font');
    if (f === 'serif') document.documentElement.classList.add('font-serif-mode');
">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light">
    <title>{{ $title ?? 'Cúc Cu Dream™' }}</title>
    <meta name="description" content="Cúc Cu Dream™ — Đánh thức giấc mơ nguyên thuỷ qua nghệ thuật">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen" style="background:#F5EDE0; overflow-x:hidden;">

{{-- Top Nav --}}
<header style="background:#FFFFFF; border-bottom:1px solid #E1E1E1; position:sticky; top:0; z-index:50;">
    <div class="max-w-screen-xl mx-auto px-4 h-14 flex items-center justify-between gap-2 sm:gap-4" style="min-width:0;">
        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex items-center gap-1 shrink-0">
            <span style="font-size:1.3rem; font-weight:800; letter-spacing:-0.02em; color:#D4896E;">Cúc Cu</span>
            <span style="font-size:1.3rem; font-weight:800; letter-spacing:-0.02em; color:#7B8B6F;">Dream</span><span style="font-size:0.75rem; font-weight:700; color:#8B7E74;">™</span>
        </a>

        {{-- Search --}}
        <div class="hidden md:flex items-center flex-1 max-w-xs mx-4">
            <div class="relative w-full">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4" style="color:#8B7E74;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="search" placeholder="Tìm kiếm..." class="input" style="padding-left:2.25rem; font-size:0.8rem; height:36px;"
                    x-data x-on:keydown.enter="window.location.href='{{ route('search') }}?q=' + encodeURIComponent($el.value)">
            </div>
        </div>

        {{-- Right section --}}
        <div class="flex items-center gap-2 shrink-0">
            @auth
                {{-- XP pill --}}
                <div class="hidden sm:flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold" style="background:#FFF9F0; border:1px solid #E1E1E1; color:#2E2E2E;">
                    <span class="w-2 h-2 rounded-full inline-block" style="background:#D4896E;"></span>
                    Lv.{{ auth()->user()->level }}
                </div>

                {{-- Messages (hidden for kid accounts) --}}
                @if(!auth()->user()->isKid())
                <a href="{{ route('messages') }}" class="relative" style="color:#8B7E74; padding:0.25rem;" title="Tin nhắn">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                </a>
                @endif

                {{-- Notifications --}}
                <livewire:notification-bell />

                {{-- User menu --}}
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center gap-2 rounded-lg px-2 py-1 transition-colors hover:bg-gray-100">
                        <img src="{{ auth()->user()->avatar_url }}" alt="" class="avatar w-7 h-7">
                        <svg class="w-3.5 h-3.5" style="color:#8B7E74;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition
                        style="position:absolute; right:0; top:calc(100% + 8px); background:#FFFFFF; border:1px solid #E1E1E1; border-radius:0.75rem; min-width:200px; padding:0.375rem; z-index:100; box-shadow:0 8px 32px rgba(0,0,0,0.10);">
                        <div class="px-3 py-2 mb-1" style="border-bottom:1px solid #E1E1E1;">
                            <p style="font-size:0.8rem; font-weight:600; color:#1A1A1A;">{{ auth()->user()->name }}</p>
                            <p style="font-size:0.7rem; color:#8B7E74;">{{ auth()->user()->job_stage }}</p>
                        </div>
                        <a href="{{ route('profile', auth()->user()->username ?? auth()->id()) }}" class="nav-item" style="font-size:0.8rem;">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Profile của tôi
                        </a>
                        <a href="{{ route('affiliate') }}" class="nav-item" style="font-size:0.8rem;">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Affiliate
                        </a>
                        <div style="height:1px; background:#E1E1E1; margin:0.25rem 0;"></div>
                        {{-- Font toggle --}}
                        <button
                            x-data="{ isSerif: document.documentElement.classList.contains('font-serif-mode') }"
                            @click="
                                isSerif = !isSerif;
                                document.documentElement.classList.toggle('font-serif-mode', isSerif);
                                localStorage.setItem('ccd_font', isSerif ? 'serif' : 'sans');
                            "
                            class="nav-item w-full" style="font-size:0.8rem; justify-content:space-between;">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16"/></svg>
                                Font chữ
                            </span>
                            <span x-text="isSerif ? 'Serif' : 'Sans'" style="font-size:0.7rem; color:#8B7E74;"></span>
                        </button>
                        <div style="height:1px; background:#E1E1E1; margin:0.25rem 0;"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="nav-item w-full" style="font-size:0.8rem; color:#991B1B;">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Đăng xuất
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn btn-ghost text-sm">Đăng nhập</a>
                <a href="{{ route('register') }}" class="btn btn-primary text-sm">Tham gia</a>
            @endauth
        </div>
    </div>
</header>

{{-- Main layout --}}
<div class="max-w-screen-xl mx-auto px-4 py-5" style="min-width:0;">
    <div class="flex gap-5" style="min-width:0;">

        {{-- LEFT SIDEBAR --}}
        <aside class="hidden lg:flex flex-col gap-3 w-52 shrink-0" @if(!empty($hideSidebar)) style="display:none !important;" @endif>
            {{-- Navigation --}}
            <nav class="card" style="padding:0.5rem;">
                <a href="{{ route('feed') }}" class="nav-item {{ request()->routeIs('feed') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Bảng tin
                </a>
                <a href="{{ route('cot') }}" class="nav-item {{ request()->routeIs('cot') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                    Tâm Đắc
                </a>
                <a href="{{ route('signals') }}" class="nav-item {{ request()->routeIs('signals') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Thành Quả
                </a>
                <a href="{{ route('qa') }}" class="nav-item {{ request()->routeIs('qa') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Hỏi đáp
                </a>
                <a href="{{ route('challenge') }}" class="nav-item {{ request()->routeIs('challenge*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"/></svg>
                    Challenge
                </a>
                <a href="{{ route('leaderboard') }}" class="nav-item {{ request()->routeIs('leaderboard') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Leaderboard
                </a>
                <a href="{{ route('academy') }}" class="nav-item {{ request()->routeIs('academy*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    Khóa học
                </a>
                @auth
                @if(auth()->user()->isParent())
                <a href="{{ route('family') }}" class="nav-item {{ request()->routeIs('family*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M12 4a3 3 0 110 6 3 3 0 010-6zm7 4a2 2 0 110 4 2 2 0 010-4zM5 8a2 2 0 110 4 2 2 0 010-4z"/></svg>
                    Gia đình
                </a>
                @endif
                @if(!auth()->user()->isKid())
                <a href="{{ route('marketplace') }}" class="nav-item {{ request()->routeIs('marketplace') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                    Marketplace
                </a>
                @endif
                @endauth
                @can('admin')
                <div style="height:1px; background:#E8E4DE; margin:0.25rem 0;"></div>
                <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/></svg>
                    Admin
                </a>
                @endcan
            </nav>

            {{-- Burning Zone --}}
            <livewire:sidebar-burning-zone />

        </aside>

        {{-- MAIN CONTENT --}}
        <main class="flex-1 min-w-0">
            {{ $slot }}
        </main>

        {{-- RIGHT SIDEBAR --}}
        <aside class="hidden xl:flex flex-col gap-3 w-60 shrink-0" @if(!empty($hideSidebar)) style="display:none !important;" @endif>
            {{-- My EXP card --}}
            @auth
            <livewire:sidebar-my-xp />
            @endauth

            {{-- Affiliate CTA --}}
            @auth
            @if(!auth()->user()->isKid())
            <a href="{{ route('affiliate') }}" class="block" style="background:linear-gradient(135deg, #D4896E, #C9A84C); border-radius:0.75rem; padding:1rem; text-decoration:none; transition:transform 0.15s, box-shadow 0.15s;">
                <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:0.5rem;">
                    <span style="font-size:1.5rem;">🎁</span>
                    <p style="font-size:0.85rem; font-weight:800; color:#FFFFFF; margin:0;">Giới thiệu bạn bè</p>
                </div>
                <p style="font-size:0.75rem; font-weight:500; color:rgba(255,255,255,0.9); margin:0; line-height:1.4;">Mời phụ huynh khác tham gia & nhận hoa hồng 20%</p>
                <div style="margin-top:0.625rem; background:rgba(255,255,255,0.25); border-radius:0.375rem; padding:0.375rem 0.625rem; text-align:center;">
                    <span style="font-size:0.75rem; font-weight:700; color:#FFFFFF;">Tham gia ngay →</span>
                </div>
            </a>
            @endif
            @endauth

            {{-- Community Challenge --}}
            <livewire:sidebar-challenge />

            {{-- Leaderboard mini --}}
            <livewire:sidebar-leaderboard />

            {{-- Upcoming Challenges --}}
            <livewire:sidebar-challenges />
        </aside>

    </div>
</div>

{{-- Post Modal (global) --}}
<livewire:post-modal />

{{-- Toast Notifications --}}
<div x-data="{ toasts: [], add(e) { const t = { id: Date.now(), message: e.detail.message, type: e.detail.type || 'info' }; this.toasts.push(t); setTimeout(() => this.toasts = this.toasts.filter(x => x.id !== t.id), 4000); } }"
     @toast.window="add($event)"
     style="position:fixed; bottom:5rem; right:1rem; z-index:9999; display:flex; flex-direction:column; gap:0.5rem;">
    <template x-for="toast in toasts" :key="toast.id">
        <div x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             :style="'padding:0.625rem 1rem; border-radius:0.5rem; font-size:0.8rem; font-weight:500; box-shadow:0 4px 12px rgba(0,0,0,0.15); max-width:320px;'
                + (toast.type === 'error' ? 'background:#FEE2E2; color:#991B1B; border:1px solid #FECACA;' :
                   toast.type === 'success' ? 'background:#D1FAE5; color:#065F46; border:1px solid #A7F3D0;' :
                   'background:#FFFFFF; color:#1A1A1A; border:1px solid #E1E1E1;')"
             x-text="toast.message">
        </div>
    </template>
</div>

{{-- Mobile Bottom Nav (lg:hidden) --}}
@auth
<nav class="lg:hidden" style="position:fixed; bottom:0; left:0; right:0; background:#FFFFFF; border-top:1px solid #E1E1E1; z-index:50; padding:0.375rem 0; padding-bottom:env(safe-area-inset-bottom, 0.375rem);">
    <div class="flex items-center justify-around">
        <a href="{{ route('feed') }}" style="display:flex; flex-direction:column; align-items:center; gap:0.125rem; font-size:0.7rem; font-weight:600; color:{{ request()->routeIs('feed') ? '#D4896E' : '#8B7E74' }}; text-decoration:none; padding:0.25rem;">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Feed
        </a>
        <a href="{{ route('qa') }}" style="display:flex; flex-direction:column; align-items:center; gap:0.125rem; font-size:0.7rem; font-weight:600; color:{{ request()->routeIs('qa') ? '#D4896E' : '#8B7E74' }}; text-decoration:none; padding:0.25rem;">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Hỏi đáp
        </a>
        @if(auth()->user()->isParent())
        <a href="{{ route('family') }}" style="display:flex; flex-direction:column; align-items:center; gap:0.125rem; font-size:0.7rem; font-weight:600; color:{{ request()->routeIs('family*') ? '#D4896E' : '#8B7E74' }}; text-decoration:none; padding:0.25rem;">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M12 4a3 3 0 110 6 3 3 0 010-6zm7 4a2 2 0 110 4 2 2 0 010-4zM5 8a2 2 0 110 4 2 2 0 010-4z"/></svg>
            Gia đình
        </a>
        @else
        <a href="{{ route('challenge') }}" style="display:flex; flex-direction:column; align-items:center; gap:0.125rem; font-size:0.7rem; font-weight:600; color:{{ request()->routeIs('challenge*') ? '#D4896E' : '#8B7E74' }}; text-decoration:none; padding:0.25rem;">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/></svg>
            Challenge
        </a>
        @endif
        <a href="{{ route('leaderboard') }}" style="display:flex; flex-direction:column; align-items:center; gap:0.125rem; font-size:0.7rem; font-weight:600; color:{{ request()->routeIs('leaderboard') ? '#D4896E' : '#8B7E74' }}; text-decoration:none; padding:0.25rem;">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            Rank
        </a>
        <a href="{{ route('profile', auth()->user()->username ?? auth()->id()) }}" style="display:flex; flex-direction:column; align-items:center; gap:0.125rem; font-size:0.7rem; font-weight:600; color:{{ request()->routeIs('profile') ? '#D4896E' : '#8B7E74' }}; text-decoration:none; padding:0.25rem;">
            <img src="{{ auth()->user()->avatar_url }}" style="width:22px; height:22px; border-radius:50%; object-fit:cover;" alt="">
            Tôi
        </a>
    </div>
</nav>
@endauth

{{-- Bottom padding for mobile nav --}}
<div class="lg:hidden" style="height:60px;"></div>

@livewireScripts
</body>
</html>
