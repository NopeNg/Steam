@extends('Admins.layouts.admin')

@section('title', 'Chỉnh sửa Liên kết Game - Nhà cung cấp')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
        <h1 class="h3 fw-bold">Chỉnh sửa Liên kết Game - Nhà cung cấp</h1>
        <a href="{{ route('admin.suppliers.mapping') }}" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Quay lại
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4">
            <i class="fas fa-exclamation-circle me-1"></i> {{ $errors->first() }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-4">
            <form action="{{ route('admin.suppliers.mapping.update', $mapping->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Game <span class="text-danger">*</span></label>
                        <select class="form-select" name="game_id" required>
                            <option value="">-- Chọn game --</option>
                            @foreach($games as $game)
                                <option value="{{ $game->id }}" {{ old('game_id', $mapping->game_id) == $game->id ? 'selected' : '' }}>
                                    {{ $game->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Nhà cung cấp <span class="text-danger">*</span></label>
                        <select class="form-select" name="supplier_provider_id" required>
                            <option value="">-- Chọn nhà cung cấp --</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_provider_id', $mapping->supplier_provider_id) == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }} ({{ $supplier->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Mã game bên supplier (nếu khác)</label>
                        <input type="text" class="form-control" name="supplier_game_id" value="{{ old('supplier_game_id', $mapping->supplier_game_id) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Trạng thái <span class="text-danger">*</span></label>
                        <select class="form-select" name="status" required>
                            <option value="Active" {{ old('status', $mapping->status) == 'Active' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="Inactive" {{ old('status', $mapping->status) == 'Inactive' ? 'selected' : '' }}>Tắt</option>
                        </select>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.suppliers.mapping') }}" class="btn btn-secondary px-4">Hủy</a>
                    <button type="submit" class="btn btn-success px-5 fw-bold">
                        <i class="fas fa-save me-1"></i> Cập nhật
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection