<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light">
    <title>Cúc Cu Dream™ — Đánh thức giấc mơ nguyên thuỷ qua nghệ thuật</title>
    <meta name="description" content="Nền tảng giáo dục nghệ thuật & cộng đồng sáng tạo cho trẻ em và người lớn. Học vẽ, kể chuyện, chữa lành qua nét cọ.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        /* Landing-specific animations */
        @keyframes float { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-8px); } }
        @keyframes fadeInUp { from { opacity:0; transform:translateY(30px); } to { opacity:1; transform:translateY(0); } }
        @keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
        .animate-float { animation: float 4s ease-in-out infinite; }
        .fade-in-up { animation: fadeInUp 0.8s ease-out both; }
        .fade-in { animation: fadeIn 0.6s ease-out both; }
        .delay-1 { animation-delay: 0.15s; }
        .delay-2 { animation-delay: 0.3s; }
        .delay-3 { animation-delay: 0.45s; }
        .delay-4 { animation-delay: 0.6s; }
        .delay-5 { animation-delay: 0.75s; }

        /* Smooth scroll */
        html { scroll-behavior: smooth; }

        /* Gradient text */
        .gradient-text {
            background: linear-gradient(135deg, #D4896E, #7B8B6F);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body style="background:#F5EDE0; color:#2D2926; font-family:'Nunito',sans-serif; font-weight:500; -webkit-font-smoothing:antialiased;">
    {{ $slot }}
    @livewireScripts
</body>
</html>
