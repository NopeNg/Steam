@extends('Admins.layouts.admin')

@section('title', 'Tạo Key Giveaway - GameKey')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
        <h1 class="h3 fw-bold">Tạo Key Giveaway (Phát quà)</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('admin.keys.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-4">
            <form action="{{ route('admin.keys.custom.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="key_code" class="form-label fw-bold">Mã Key <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="key_code" name="key_code" required placeholder="Ví dụ: A1B2-C3D4-E5F6-G7H8">
                    </div>
                    <div class="col-md-6">
                        <label for="game_version_id" class="form-label fw-bold">Game / Phiên bản <span class="text-danger">*</span></label>
                        <select class="form-select" id="game_version_id" name="game_version_id" required>
                            <option value="">-- Chọn game & phiên bản --</option>
                            @foreach($games as $game)
                                <optgroup label="{{ $game->name }}">
                                    @foreach($game->versions as $version)
                                        <option value="{{ $version->id }}">
                                            {{ $version->version_name }} ({{ number_format($version->price) }}đ)
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label for="note" class="form-label fw-bold">Ghi chú</label>
                        <input type="text" class="form-control" id="note" name="note" placeholder="Ví dụ: Tặng CS2 - Event 2026">
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.keys.index') }}" class="btn btn-secondary px-4">Hủy bỏ</a>
                    <button type="submit" class="btn btn-success px-5 fw-bold">
                        <i class="fas fa-save me-1"></i> Tạo Key Giveaway
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection