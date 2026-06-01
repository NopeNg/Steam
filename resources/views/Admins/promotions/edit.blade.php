@extends('Admins.layouts.admin')

@section('title', 'Sửa Khuyến Mãi - GameKey')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
        <h1 class="h3 fw-bold">Chỉnh Sửa Chiến Dịch #{{ $promotion->id }}</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('admin.promotions.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-4">
            <form action="{{ route('admin.promotions.update', $promotion->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-8">
                        <label for="campaign_name" class="form-label fw-bold">Tên chiến dịch <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="campaign_name" name="campaign_name" value="{{ $promotion->campaign_name }}" required>
                    </div>
                    <div class="col-md-4">
                        <label for="discount_percent" class="form-label fw-bold">Mức giảm giá (%) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="discount_percent" name="discount_percent" min="1" max="100" value="{{ $promotion->discount_percent }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="start_time" class="form-label fw-bold">Thời gian bắt đầu <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control" id="start_time" name="start_time" value="{{ \Carbon\Carbon::parse($promotion->start_time)->format('Y-m-d\TH:i') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="end_time" class="form-label fw-bold">Thời gian kết thúc <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control" id="end_time" name="end_time" value="{{ \Carbon\Carbon::parse($promotion->end_time)->format('Y-m-d\TH:i') }}" required>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.promotions.index') }}" class="btn btn-secondary px-4">Hủy bỏ</a>
                    <button type="submit" class="btn btn-primary px-5 fw-bold"><i class="fas fa-save me-1"></i> Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection