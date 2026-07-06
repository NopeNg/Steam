@extends('Players.Layouts.auth')

@section('content')
<div class="container auth-card" style="max-width: 800px;">
    <div class="row">
        <div class="col-md-7">
            <h2 class="text-white mb-4">Đăng nhập</h2>
<form id="loginForm" action="{{ route('login') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label class="form-label text-secondary small fw-bold text-uppercase">Đăng nhập bằng email</label>
        <input type="email" name="email" class="form-control bg-dark text-white border-0" required>
    </div>
    <div class="mb-3">
        <label class="form-label text-secondary small fw-bold text-uppercase">Mật khẩu</label>
        <input type="password" name="password" class="form-control bg-dark text-white border-0" required>
    </div>

    <!-- Thêm Checkbox tại đây -->
<div class="mb-3">
    <div class="form-check">
        <input type="checkbox" name="agree" id="agreeCheckbox" class="form-check-input">
        <label class="form-check-label text-white small" for="agreeCheckbox">
            Tôi đã đọc và đồng ý với 
            <a href="{{ route('privacy') }}" class="text-sky-400 hover:text-white transition">Chính sách bảo mật</a> 
            & 
            <a href="{{ route('terms') }}" class="text-sky-400 hover:text-white transition">Điều khoản dịch vụ</a>
        </label>
    </div>
</div>

    <!-- Nút Đăng nhập với trạng thái disabled mặc định -->
    <button type="submit" id="loginButton" class="btn btn-steam w-100 py-2" disabled>
        Đăng nhập
    </button>
</form>

<script>
    const checkbox = document.getElementById('agreeCheckbox');
    const button = document.getElementById('loginButton');

    checkbox.addEventListener('change', function() {
        // Nút sẽ được bật (disabled = false) nếu checkbox được chọn
        button.disabled = !this.checked;
    });
</script>
              @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger" style="color: red; background-color: #fce8e6; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
        {{ session('error') }}
    </div>
@endif
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