@extends('Players.Layouts.auth')

@section('content')
<div class="container auth-card" style="max-width: 800px;">
    <div class="row">
        <div class="col-md-7">
            <h2 class="text-white mb-4">Đăng nhập</h2>
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label text-secondary small fw-bold text-uppercase">Đăng nhập bằng tên tài khoản</label>
                    <input type="email" name="email" class="form-control bg-dark text-white border-0" required>
                </div>
                <div class="mb-3">
                    <label class="form-label text-secondary small fw-bold text-uppercase">Mật khẩu</label>
                    <input type="password" name="password" class="form-control bg-dark text-white border-0" required>
                </div>
                <button type="submit" class="btn btn-steam w-100 py-2">Đăng nhập</button>
            </form>
            <div class="mt-4 text-center">
    <p class="text-secondary small">
        Bạn chưa có tài khoản? 
        <a href="{{ route('register') }}" class="text-primary text-decoration-none fw-bold">
            Tạo tài khoản mới
        </a>
    </p>
</div>
        </div>
        <div class="col-md-5 d-none d-md-flex flex-column align-items-center justify-content-center border-start border-secondary">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=SteamLogin" width="120" class="bg-white p-2">
            <p class="text-secondary small mt-3">Đăng nhập bằng mã QR</p>
        </div>
    </div>
</div>
@endsection 