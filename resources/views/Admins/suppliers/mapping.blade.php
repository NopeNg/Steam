@extends('Admins.layouts.admin')

@section('title', 'Liên kết Game - Nhà cung cấp')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
        <h1 class="h3 fw-bold">Liên kết Game với Nhà cung cấp</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.suppliers.mapping.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus me-1"></i> Liên kết mới
            </a>
            <a href="{{ route('admin.suppliers.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Quay lại
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Bộ lọc & Tìm kiếm --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.suppliers.mapping') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-bold small">Tìm game</label>
                    <input type="text" class="form-control form-control-sm" name="search_game"
                        placeholder="Nhập tên game..." value="{{ request('search_game') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold small">Nhà cung cấp</label>
                    <select class="form-select form-select-sm" name="filter_supplier">
                        <option value="">Tất cả</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ request('filter_supplier') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold small">Trạng thái</label>
                    <select class="form-select form-select-sm" name="filter_status">
                        <option value="">Tất cả</option>
                        <option value="Active" {{ request('filter_status') == 'Active' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="Inactive" {{ request('filter_status') == 'Inactive' ? 'selected' : '' }}>Tắt</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary btn-sm me-1">
                        <i class="fas fa-filter me-1"></i> Lọc
                    </button>
                    <a href="{{ route('admin.suppliers.mapping') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-undo me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Bulk Action Bar --}}
    <div id="bulkActionBar" class="card border-0 shadow-sm mb-4 d-none border-start border-primary border-4 bg-body-tertiary">
        <div class="card-body py-2">
            <form method="POST" action="{{ route('admin.suppliers.mapping.bulk-update') }}" class="row g-2 align-items-end" id="bulkForm">
                @csrf
                <div class="col-auto">
                    <strong class="text-primary-emphasis"><span id="bulkCount">0</span> liên kết được chọn</strong>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold small">Đổi nhà cung cấp</label>
                    <select class="form-select form-select-sm" name="bulk_supplier_id">
                        <option value="">-- Giữ nguyên --</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold small">Đổi trạng thái</label>
                    <select class="form-select form-select-sm" name="bulk_status">
                        <option value="">-- Giữ nguyên --</option>
                        <option value="Active">Hoạt động</option>
                        <option value="Inactive">Tắt</option>
                    </select>
                </div>
                <div class="col-auto">
                    <input type="hidden" name="mapping_ids" id="mappingIdsInput" value="">
                    <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Cập nhật các liên kết đã chọn?')">
                        <i class="fas fa-save me-1"></i> Cập nhật hàng loạt
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearAllSelections()">
                        <i class="fas fa-times me-1"></i> Bỏ chọn
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center py-3">
            <span class="text-muted small">
                @if(request('search_game') || request('filter_supplier') || request('filter_status'))
                    <i class="fas fa-filter me-1"></i> Đang lọc: 
                    {{ request('search_game') ? 'Game: "'.request('search_game').'"' : '' }}
                    {{ request('filter_supplier') ? ' | NCC: '.$suppliers->firstWhere('id', request('filter_supplier'))?->name : '' }}
                    {{ request('filter_status') ? ' | Trạng thái: '.(request('filter_status') == 'Active' ? 'Hoạt động' : 'Tắt') : '' }}
                @else
                    <i class="fas fa-list me-1"></i> Danh sách liên kết
                @endif
            </span>
            <div>
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="selectAllPage()">
                    <i class="fas fa-check-double me-1"></i> Chọn trang
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="deselectAll()">
                    <i class="fas fa-times me-1"></i> Bỏ chọn
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="text-muted small border-bottom">
                        <tr>
                            <th style="width: 40px;">
                                <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll(this)">
                            </th>
                            <th class="px-2">ID</th>
                            <th>GAME</th>
                            <th>NHÀ CUNG CẤP</th>
                            <th>MÃ GAME (SUPPLIER)</th>
                            <th>TRẠNG THÁI</th>
                            <th class="text-end px-4">THAO TÁC</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mappings as $mapping)
                            <tr class="mapping-row" data-id="{{ $mapping->id }}">
                                <td>
                                    <input type="checkbox" class="mapping-checkbox" value="{{ $mapping->id }}" onchange="updateBulkBar()">
                                </td>
                                <td class="px-2 fw-bold">#{{ $mapping->id }}</td>
                                <td class="fw-bold text-info">{{ $mapping->game->name ?? 'N/A' }}</td>
                                <td>
                                    @if($mapping->supplierProvider)
                                        <span class="badge bg-primary rounded-pill">{{ $mapping->supplierProvider->name }}</span>
                                    @else
                                        <span class="badge bg-secondary">(Đã xóa)</span>
                                    @endif
                                </td>
                                <td><code>{{ $mapping->supplier_game_id ?? '-' }}</code></td>
                                <td>
                                    @if($mapping->status == 'Active')
                                        <span class="badge bg-success rounded-pill px-3">Hoạt động</span>
                                    @else
                                        <span class="badge bg-secondary rounded-pill px-3">Tắt</span>
                                    @endif
                                </td>
                                <td class="text-end px-4">
                                    <a href="{{ route('admin.suppliers.mapping.edit', $mapping->id) }}" class="btn btn-sm btn-outline-primary" title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.suppliers.mapping.destroy', $mapping->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa"
                                            onclick="return confirm('Xóa liên kết này?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fas fa-plug fs-2 mb-3 opacity-50"></i>
                                    <p class="mb-0">
                                        @if(request('search_game') || request('filter_supplier') || request('filter_status'))
                                            Không tìm thấy liên kết phù hợp.
                                            <a href="{{ route('admin.suppliers.mapping') }}" class="ms-1">Xóa bộ lọc</a>
                                        @else
                                            Chưa có liên kết nào.
                                            <a href="{{ route('admin.suppliers.mapping.create') }}">Tạo liên kết đầu tiên</a>
                                        @endif
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer border-0 py-3">
            <div class="d-flex justify-content-end">
                {{ $mappings->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<script>
function updateBulkBar() {
    const checkboxes = document.querySelectorAll('.mapping-checkbox:checked');
    const bar = document.getElementById('bulkActionBar');
    const count = document.getElementById('bulkCount');
    const idsInput = document.getElementById('mappingIdsInput');

    const ids = Array.from(checkboxes).map(cb => cb.value);
    count.textContent = ids.length;
    idsInput.value = ids.join(',');

    if (ids.length > 0) {
        bar.classList.remove('d-none');
    } else {
        bar.classList.add('d-none');
    }
}

function toggleSelectAll(masterCheckbox) {
    document.querySelectorAll('.mapping-checkbox').forEach(cb => {
        cb.checked = masterCheckbox.checked;
    });
    updateBulkBar();
}

function selectAllPage() {
    document.querySelectorAll('.mapping-checkbox').forEach(cb => cb.checked = true);
    document.getElementById('selectAllCheckbox').checked = true;
    updateBulkBar();
}

function deselectAll() {
    document.querySelectorAll('.mapping-checkbox').forEach(cb => cb.checked = false);
    document.getElementById('selectAllCheckbox').checked = false;
    updateBulkBar();
}

function clearAllSelections() {
    deselectAll();
}

// Submit bulk form: chuyển mảng ids từ chuỗi comma-separated
document.getElementById('bulkForm').addEventListener('submit', function(e) {
    const idsStr = document.getElementById('mappingIdsInput').value;
    const ids = idsStr.split(',').filter(id => id);
    
    // Xóa input hidden cũ và thêm từng input mới
    const existingInputs = this.querySelectorAll('input[name="mapping_ids[]"]');
    existingInputs.forEach(inp => inp.remove());
    
    ids.forEach(id => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'mapping_ids[]';
        input.value = id;
        this.appendChild(input);
    });
});
</script>

<style>
.mapping-row:hover {
    background-color: #f8f9ff;
}
.mapping-checkbox:checked + .mapping-row {
    background-color: #e8f4fd;
}
</style>
@endsection