<!DOCTYPE html>
<html lang="vi" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập Quản trị</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #121212;
            color: #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .login-card {
            background-color: #1e1e1e;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            border: 1px solid #2d2d2d;
            padding: 2.5rem;
            width: 100%;
            max-width: 420px;
        }
        .form-control {
            background-color: #2d2d2d;
            border: 1px solid #3d3d3d;
            color: #fff;
            border-radius: 8px;
            padding: 12px 16px;
        }
        .form-control:focus {
            background-color: #333;
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
            color: #fff;
        }
        .btn-login {
            background-color: #0d6efd;
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="text-center mb-4">
        <h3 class="fw-bold text-white"><i class="fas fa-gamepad text-primary me-2"></i>Admin Panel</h3>
    </div>

    @if($errors->any())
        <div class="alert alert-danger border-0 rounded-3 small">
            <i class="fas fa-exclamation-circle me-1"></i> {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('admin.login') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label small text-muted fw-bold">Email quản trị</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
        </div>
        <div class="mb-4">
            <label class="form-label small text-muted fw-bold">Mật khẩu</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-login btn-primary w-100">Đăng nhập</button>
    </form>
</div>

</body>
</html>