@extends('Admins.layouts.admin')

@section('title', 'Chi tiết Game - GameKey')

@section('content')
    <div class="container-fluid py-4">
        <div
            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
            <h1 class="h3 fw-bold">Chi tiết Sản phẩm: <span class="text-primary">{{ $game->name }}</span></h1>
            <div class="btn-toolbar mb-2 mb-md-0 gap-2">
                <a href="{{ route('admin.games.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                </a>
                <a href="{{ route('admin.games.edit', $game->id) }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-edit me-1"></i> Chỉnh sửa
                </a>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-sm rounded-3 mb-4 h-100">
                    <div class="card-body p-4 text-center">
                        <img src="{{ $game->cover_image }}" alt="{{ $game->name }}" class="img-fluid rounded shadow mb-3"
                            style="max-height: 400px; object-fit: contain;">
                        <h5 class="fw-bold mb-1">{{ $game->name }}</h5>
                        <p class="text-muted small mb-3">ID Hệ thống: #{{ $game->id }}</p>

                        <span
                            class="badge {{ $game->status == 'Active' ? 'bg-success' : 'bg-secondary' }} px-3 py-2 fs-6 rounded-pill">
                            {{ $game->status == 'Active' ? 'Đang bán (Active)' : 'Tạm ẩn (Inactive)' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-8">
                <div class="card border-0 shadow-sm rounded-3 mb-4 h-100">
                    <div class="card-body p-4">
                        <h5 class="fw-bold text-primary mb-4"><i class="fas fa-info-circle me-2"></i>Thông tin cơ bản</h5>

                        <div class="row mb-3">
                            <div class="col-sm-4 text-muted fw-bold">Nhà phát hành:</div>
                            <div class="col-sm-8">{{ $game->publisher ?? 'Chưa cập nhật' }}</div>
                        </div>
                        <hr class="text-muted opacity-25">

                        <div class="row mb-3">
                            <div class="col-sm-4 text-muted fw-bold">Nhà phát triển:</div>
                            <div class="col-sm-8">{{ $game->developer ?? 'Chưa cập nhật' }}</div>
                        </div>
                        <hr class="text-muted opacity-25">

                        <div class="row mb-3">
                            <div class="col-sm-4 text-muted fw-bold">Ngày phát hành:</div>
                            <div class="col-sm-8">{{ \Carbon\Carbon::parse($game->release_date)->format('d/m/Y') }}</div>
                        </div>
                        <hr class="text-muted opacity-25">

                        <div class="row mb-3">
                            <div class="col-sm-12 text-muted fw-bold mb-2">Mô tả chi tiết:</div>
                            <div class="col-sm-12 p-3 border rounded-3">
                                {!! nl2br(e($game->description)) !!}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-12 text-muted fw-bold mb-2">Cấu hình yêu cầu:</div>
                            <div class="col-sm-12 p-3 border rounded-3 text-break">
                                {!! nl2br(e($game->requirements)) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($game->images->count() > 0)
            <div class="row mt-2 mb-5">
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-body p-4">
                            <h5 class="fw-bold text-primary mb-4"><i class="fas fa-images me-2"></i>Bộ sưu tập ảnh phụ
                                ({{ $game->images->count() }})</h5>
                            <div class="row g-3">
                                @foreach($game->images as $img)
                                    <div class="col-md-4 col-lg-3">
                                        <div class="card h-100 border shadow-sm">
                                            <div class="position-relative">
                                                <img src="{{ $img->image_path }}" class="card-img-top"
                                                    style="height: 160px; object-fit: cover;">
                                                <span
                                                    class="badge bg-primary position-absolute top-0 start-0 m-2">{{ $img->image_type }}</span>
                                                <span
                                                    class="badge bg-dark position-absolute top-0 end-0 m-2">{{ $img->game_part }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection