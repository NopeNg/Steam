@extends('Admins.layouts.admin')

@section('title', 'Chỉnh sửa Nhà cung cấp - GameKey')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
        <h1 class="h3 fw-bold">Chỉnh sửa Nhà cung cấp</h1>
        <a href="{{ route('admin.suppliers.index') }}" class="btn btn-sm btn-outline-secondary">
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
            <form action="{{ route('admin.suppliers.update', $supplier->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Tên nhà cung cấp <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" value="{{ old('name', $supplier->name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Mã code <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="code" value="{{ old('code', $supplier->code) }}" required>
                        <div class="form-text">Mã định danh duy nhất (VD: SUPPLIER_A)</div>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label fw-bold">Base URL <span class="text-danger">*</span></label>
                        <input type="url" class="form-control" name="base_url" value="{{ old('base_url', $supplier->base_url) }}" required placeholder="https://api.supplier.com">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">API Key</label>
                        <input type="text" class="form-control" name="api_key" value="{{ old('api_key', $supplier->api_key) }}" placeholder="Để trống nếu không cần">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">API Key Header <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="api_key_header" value="{{ old('api_key_header', $supplier->api_key_header) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Timeout (giây) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="timeout" value="{{ old('timeout', $supplier->timeout) }}" min="1" max="120" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Priority <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="priority" value="{{ old('priority', $supplier->priority) }}" min="0" max="999" required>
                        <div class="form-text">Cao hơn = ưu tiên trước</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Purchase Endpoint <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="purchase_endpoint" value="{{ old('purchase_endpoint', $supplier->purchase_endpoint) }}" required placeholder="/api/purchase">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Verify Endpoint <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="verify_endpoint" value="{{ old('verify_endpoint', $supplier->verify_endpoint) }}" required placeholder="/api/verify-key">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Trạng thái <span class="text-danger">*</span></label>
                        <select class="form-select" name="status" required>
                            <option value="Active" {{ old('status', $supplier->status) == 'Active' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="Inactive" {{ old('status', $supplier->status) == 'Inactive' ? 'selected' : '' }}>Tắt</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Headers (JSON)</label>
                        <input type="text" class="form-control" name="headers" value="{{ old('headers', is_array($supplier->headers) ? json_encode($supplier->headers) : $supplier->headers) }}" placeholder='{"Content-Type": "application/json"}'>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold">Ghi chú</label>
                        <textarea class="form-control" name="notes" rows="2">{{ old('notes', $supplier->notes) }}</textarea>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.suppliers.index') }}" class="btn btn-secondary px-4">Hủy</a>
                    <button type="submit" class="btn btn-success px-5 fw-bold">
                        <i class="fas fa-save me-1"></i> Cập nhật
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection