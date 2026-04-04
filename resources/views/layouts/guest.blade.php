<!DOCTYPE html>
<html lang="vi">
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
<body class="min-h-screen flex flex-col items-center justify-center p-8" style="background:#F5EDE0;">
    <div class="w-full max-w-md">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <a href="/" class="text-3xl font-extrabold tracking-tight" style="color:#2D2926;">
                <span style="color:#D4896E;">Cúc Cu</span> <span style="color:#7B8B6F;">Dream</span>™
            </a>
            <p class="text-base mt-1 font-semibold" style="color:#8B7E74;">Đánh thức giấc mơ nguyên thuỷ qua nghệ thuật</p>
        </div>

        {{ $slot }}
    </div>
    @livewireScripts
</body>
</html>
