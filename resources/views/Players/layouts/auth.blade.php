<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>SteamKey Auth</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #1b2838; color: #c7d5e0; font-family: "Motiva Sans", Arial, sans-serif; }
        .auth-card { background-color: #171a21; border-top: 4px solid #1a9fff; padding: 2rem; }
        .btn-steam { background: linear-gradient(to right, #1a9fff, #45c3f5); border: none; color: white; font-weight: bold; }
        .btn-register { background-color: #5c7e10; border: none; color: white; }
        .footer-steam { background-color: #171a21; border-top: 1px solid #2a475e; padding: 2rem 0; font-size: 12px; color: #8f98a0; }
        .footer-steam a { color: #8f98a0; text-decoration: none; }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    <header class="py-5 text-center">
        <a href="{{ route('home') }}" class="text-white text-decoration-none fs-1 fw-black">
            STEAM<span class="text-primary fw-light">KEY</span>
        </a>
    </header>

    <main class="flex-grow-1 d-flex align-items-center justify-content-center px-3 mb-5">
        @yield('content')
    </main>

    <footer class="footer-steam">
        <div class="container text-center">
            <p>© 2026 Valve Corporation. Tất cả các thương hiệu là tài sản của chủ sở hữu.</p>
        </div>
    </footer>
    
</body>
</html>