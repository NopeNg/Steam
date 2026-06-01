@extends('Players.Layouts.auth')

@section('content')
<div class="container" style="max-width: 450px;">
    <div class="auth-card p-4 rounded shadow-sm">
        <h2 class="text-white mb-4">Tạo tài khoản</h2>
        
        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label text-secondary small fw-bold text-uppercase">Tên đăng nhập</label>
                <input type="text" name="username" class="form-control bg-dark text-white border-0 py-2" required>
            </div>
            <div class="mb-3">
                <label class="form-label text-secondary small fw-bold text-uppercase">Địa chỉ Email</label>
                <input type="email" name="email" class="form-control bg-dark text-white border-0 py-2" required>
            </div>
            <div class="mb-3">
                <label class="form-label text-secondary small fw-bold text-uppercase">Họ và tên</label>
                <input type="text" name="fullname" class="form-control bg-dark text-white border-0 py-2">
            </div>
            <div class="mb-3">
                <label class="form-label text-secondary small fw-bold text-uppercase">Mật khẩu</label>
                <input type="password" name="password" class="form-control bg-dark text-white border-0 py-2" required>
            </div>
            <div class="mb-4">
                <label class="form-label text-secondary small fw-bold text-uppercase">Xác nhận mật khẩu</label>
                <input type="password" name="password_confirmation" class="form-control bg-dark text-white border-0 py-2" required>
            </div>
            
            <button type="submit" class="btn btn-register w-100 py-2 fw-bold">Tạo tài khoản</button>
        </form>
        @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

        <div class="mt-3 text-center">
            <a href="{{ route('login') }}" class="text-primary text-decoration-none small">Đã có tài khoản? Đăng nhập ngay</a>
        </div>
    </div>
</div>
@endsection