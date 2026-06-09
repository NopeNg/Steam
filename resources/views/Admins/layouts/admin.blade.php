<!DOCTYPE html>
<html lang="vi" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Quản trị hệ thống')</title>

    <script>
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-bs-theme', savedTheme);
    </script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        body {
            background-color: var(--bs-body-bg);
            transition: background-color 0.3s ease;
        }

        .sidebar {
            min-height: 100vh;
            background-color: #212529;
        }

        .sidebar a {
            color: #adb5bd;
            text-decoration: none;
            padding: 10px 15px;
            display: block;
        }

        .sidebar a:hover,
        .sidebar a.active {
            color: #fff;
            background-color: #343a40;
            border-radius: 5px;
        }

        [data-bs-theme="dark"] body {
            background-color: #121212;
        }

        [data-bs-theme="dark"] .navbar {
            background-color: #1e1e1e !important;
            border-bottom: 1px solid #333;
        }

        [data-bs-theme="dark"] .card {
            background-color: #1e1e1e;
            border: 1px solid #333 !important;
        }

        [data-bs-theme="dark"] .card-header {
            background-color: #1e1e1e;
            color: #fff;
            border-bottom: 1px solid #333;
        }

        [data-bs-theme="dark"] .table-light {
            background-color: #2c2c2c;
            color: #fff;
        }

        [data-bs-theme="dark"] .text-muted {
            color: #aaa !important;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 col-lg-2 p-3 sidebar text-white">
                <h4 class="text-center mb-4"><i class="fas fa-gamepad text-primary"></i> GameKey</h4>
                <hr>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="{{ route('admin.dashboard') }}"
                            class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt me-2"></i> Tổng quan
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('admin.games.index') }}"
                            class="{{ request()->routeIs('admin.games.*') ? 'active' : '' }}">
                            <i class="fas fa-box me-2"></i> Sản phẩm
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('admin.orders.index') }}"
                            class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                            <i class="fas fa-shopping-cart me-2"></i> Đơn hàng
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('admin.players.index') }}"
                            class="{{ request()->routeIs('admin.players.*') ? 'active' : '' }}">
                            <i class="fas fa-users me-2"></i> Người dùng
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('admin.promotions.index') }}"
                            class="{{ request()->routeIs('admin.promotions.*') ? 'active' : '' }}">
                            <i class="fas fa-gift me-2"></i> Khuyến mãi
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('admin.keys.index') }}"
                            class="{{ request()->routeIs('admin.keys.*') ? 'active' : '' }}">
                            <i class="fas fa-key me-2"></i> Đối soát API & GA
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('admin.suppliers.index') }}"
                            class="{{ request()->routeIs('admin.suppliers.*') ? 'active' : '' }}">
                            <i class="fas fa-plug me-2"></i> Nhà cung cấp Key
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('admin.reports.index') }}"
                            class="{{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                            <i class="fas fa-chart-bar me-2"></i> Báo cáo & Thống kê
                        </a>
                    </li>
                </ul>
                <div class="mt-auto p-3 w-100">
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center gap-2"
                            style="border-radius: 8px;">
                            <i class="fas fa-sign-out-alt"></i> <span>Đăng xuất</span>
                        </button>
                    </form>
                </div>
            </div>


            <div class="col-md-9 col-lg-10 p-0">
                <nav
                    class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-4 d-flex justify-content-between">
                    <span class="navbar-text fw-bold">Xin chào, Quản trị viên!</span>
                    <button id="themeToggle" class="btn btn-outline-secondary btn-sm rounded-circle"
                        style="width: 35px; height: 35px;">
                        <i class="fas fa-moon"></i>
                    </button>
                </nav>

                <div class="p-4">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const themeToggleBtn = document.getElementById('themeToggle');
            const htmlElement = document.documentElement;
            const icon = themeToggleBtn.querySelector('i');

            function updateIcon(theme) {
                if (theme === 'dark') {
                    icon.classList.remove('fa-moon');
                    icon.classList.add('fa-sun');
                    themeToggleBtn.classList.remove('btn-outline-secondary');
                    themeToggleBtn.classList.add('btn-outline-light');
                } else {
                    icon.classList.remove('fa-sun');
                    icon.classList.add('fa-moon');
                    themeToggleBtn.classList.remove('btn-outline-light');
                    themeToggleBtn.classList.add('btn-outline-secondary');
                }
            }

            const currentTheme = htmlElement.getAttribute('data-bs-theme');
            updateIcon(currentTheme);

            themeToggleBtn.addEventListener('click', function () {
                const currentTheme = htmlElement.getAttribute('data-bs-theme');
                const newTheme = currentTheme === 'light' ? 'dark' : 'light';

                htmlElement.setAttribute('data-bs-theme', newTheme);
                localStorage.setItem('theme', newTheme);
                updateIcon(newTheme);
            });
        });
    </script>
</body>

</html>