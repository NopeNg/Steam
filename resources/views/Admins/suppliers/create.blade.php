@extends('Admins.layouts.admin')

@section('title', 'Thêm Nhà cung cấp - GameKey')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
        <h1 class="h3 fw-bold">Thêm Nhà cung cấp Key</h1>
        <a href="{{ route('admin.suppliers.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Quay lại
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-4">
            <form action="{{ route('admin.suppliers.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Tên nhà cung cấp <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" value="{{ old('name') }}" required placeholder="VD: Supplier A, G2A, Eneba">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Mã code <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="code" value="{{ old('code') }}" required placeholder="VD: SUPPLIER_A">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Priority <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="priority" value="{{ old('priority', 0) }}" required min="0" max="999">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label fw-bold">Base URL <span class="text-danger">*</span></label>
                        <input type="url" class="form-control" name="base_url" value="{{ old('base_url') }}" required placeholder="VD: http://127.0.0.1:4099">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Timeout (giây) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="timeout" value="{{ old('timeout', 15) }}" required min="1" max="120">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">API Key (nếu có)</label>
                        <input type="text" class="form-control" name="api_key" value="{{ old('api_key') }}" placeholder="API Key để xác thực với supplier">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">API Key Header <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="api_key_header" value="{{ old('api_key_header', 'X-API-Key') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Purchase Endpoint <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="purchase_endpoint" value="{{ old('purchase_endpoint', '/api/purchase') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Verify Endpoint <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="verify_endpoint" value="{{ old('verify_endpoint', '/api/verify-key') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Trạng thái <span class="text-danger">*</span></label>
                        <select class="form-select" name="status" required>
                            <option value="Active" {{ old('status', 'Active') == 'Active' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Tạm ngưng</option>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label fw-bold">Headers bổ sung (JSON)</label>
                        <input type="text" class="form-control" name="headers" value="{{ old('headers') }}" placeholder='VD: {"X-Version": "2.0"}'>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold">Ghi chú</label>
                        <textarea class="form-control" name="notes" rows="2">{{ old('notes') }}</textarea>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.suppliers.index') }}" class="btn btn-secondary px-4">Hủy</a>
                    <button type="submit" class="btn btn-success px-5 fw-bold">
                        <i class="fas fa-save me-1"></i> Lưu nhà cung cấp
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection