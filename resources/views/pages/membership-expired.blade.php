<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membership hết hạn — Cúc Cu Dream™</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body style="background:#FFF9F0; min-height:100vh; display:flex; align-items:center; justify-content:center; padding:2rem; font-family:'Nunito',sans-serif;">
    <div class="card text-center" style="max-width:480px; width:100%; padding:3rem 2rem;">
        <p style="font-size:3rem; margin-bottom:1rem;">!</p>
        <h1 style="font-size:1.5rem; font-weight:800; color:#2D3436; margin-bottom:0.5rem;">Membership đã hết hạn</h1>
        <p style="color:#636E72; font-size:0.875rem; margin-bottom:2rem; line-height:1.6;">
            Cộng đồng Cúc Cu Dream tiếp tục phát triển mỗi ngày.<br>Gia hạn ngay để không bỏ lỡ bất kỳ cơ hội nào.
        </p>
        <a href="{{ route('membership.pricing') }}" class="btn btn-primary" style="width:100%; justify-content:center; padding:0.875rem; font-size:1rem;">
            Xem gói & Gia hạn
        </a>
        <form method="POST" action="{{ route('logout') }}" style="margin-top:1rem;">
            @csrf
            <button type="submit" class="btn btn-ghost" style="width:100%; justify-content:center; font-size:0.875rem;">
                Đăng xuất
            </button>
        </form>
    </div>
</body>
</html>
