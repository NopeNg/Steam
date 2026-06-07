@extends('Admins.layouts.admin')

@section('title', 'Liên kết Game - Tạo mới')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
        <h1 class="h3 fw-bold">Tạo Liên kết Game - Nhà cung cấp</h1>
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
            {{-- Bước 1: Chọn nhà cung cấp trước --}}
            <div class="alert alert-info border-0 mb-4">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Bước 1:</strong> Chọn nhà cung cấp trước. Sau đó tick chọn nhiều game để liên kết cùng lúc.
            </div>

            <form action="{{ route('admin.suppliers.mapping.store') }}" method="POST" id="mappingForm">
                @csrf
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">
                            <i class="fas fa-plug me-1"></i> Nhà cung cấp <span class="text-danger">*</span>
                        </label>
                        <select class="form-select form-select-lg" name="supplier_provider_id" id="supplierSelect" required>
                            <option value="">-- Chọn nhà cung cấp --</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_provider_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }} ({{ $supplier->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Trạng thái <span class="text-danger">*</span></label>
                        <select class="form-select" name="status" required>
                            <option value="Active" {{ old('status', 'Active') == 'Active' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Tắt</option>
                        </select>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-12">
                        <label class="form-label fw-bold">Mã game bên supplier (áp dụng cho tất cả - nếu giống nhau)</label>
                        <input type="text" class="form-control" name="supplier_game_id" value="{{ old('supplier_game_id') }}" placeholder="VD: 730 (Steam App ID) - Để trống nếu mỗi game khác nhau">
                    </div>
                </div>

                <hr>

                {{-- Bước 2: Chọn nhiều game --}}
                <div class="alert alert-warning border-0 mb-3">
                    <i class="fas fa-gamepad me-2"></i>
                    <strong>Bước 2:</strong> Tick chọn nhiều game để liên kết cùng lúc với nhà cung cấp đã chọn.
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold mb-0">Danh sách game ({{ $games->count() }}):</h6>
                    <div>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="selectAllGames()">
                            <i class="fas fa-check-double me-1"></i> Chọn tất cả
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="deselectAllGames()">
                            <i class="fas fa-times me-1"></i> Bỏ chọn
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-info" onclick="selectUnlinkedGames()">
                            <i class="fas fa-link me-1"></i> Chỉ chọn game chưa liên kết
                        </button>
                    </div>
                </div>

                <div style="max-height:500px; overflow-y:auto; border:1px solid #dee2e6; border-radius:6px; padding:10px;" id="gamesList">
                    <div class="row g-2">
                        @foreach($games as $game)
                            @php
                                $linkedSuppliers = $game->gameMappings->pluck('supplierProvider.name')->toArray();
                            @endphp
                            <div class="col-md-6 col-lg-4 game-item" data-game-id="{{ $game->id }}" data-linked="{{ count($linkedSuppliers) }}">
                                <label class="d-flex align-items-start p-2 border rounded game-label" style="cursor:pointer; transition:all 0.2s;">
                                    <input type="checkbox" name="game_ids[]" value="{{ $game->id }}" class="game-check me-2 mt-1">
                                    <div class="flex-grow-1">
                                        <div class="fw-bold text-truncate" title="{{ $game->name }}">{{ $game->name }}</div>
                                        <small class="text-muted">{{ $game->publisher ?? 'N/A' }}</small>
                                        @if(count($linkedSuppliers) > 0)
                                            <div class="mt-1">
                                                <span class="badge bg-secondary" style="font-size:9px;">
                                                    {{ count($linkedSuppliers) }} liên kết: {{ implode(', ', array_slice($linkedSuppliers, 0, 2)) }}{{ count($linkedSuppliers) > 2 ? '...' : '' }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-3 p-2 bg-light rounded">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Đã chọn: <strong id="selectedCount">0</strong> game
                    </small>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.suppliers.mapping') }}" class="btn btn-secondary px-4">Hủy</a>
                    <button type="submit" class="btn btn-success px-5 fw-bold" id="submitBtn" disabled>
                        <i class="fas fa-save me-1"></i> Lưu liên kết (<span id="submitCount">0</span> game)
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.game-label:hover {
    background-color: #f0f9ff;
    border-color: #0ea5e9 !important;
}
.game-check:checked + .flex-grow-1 {
    /* nothing */
}
.game-item.selected .game-label {
    background-color: #dbeafe;
    border-color: #3b82f6 !important;
}
</style>

<script>
function updateCount() {
    const checks = document.querySelectorAll('.game-check:checked');
    document.getElementById('selectedCount').textContent = checks.length;
    document.getElementById('submitCount').textContent = checks.length;
    const supplier = document.getElementById('supplierSelect').value;
    document.getElementById('submitBtn').disabled = !(checks.length > 0 && supplier);

    // Highlight selected items
    document.querySelectorAll('.game-item').forEach(item => {
        const cb = item.querySelector('.game-check');
        item.classList.toggle('selected', cb.checked);
    });
}

function selectAllGames() {
    document.querySelectorAll('.game-check').forEach(cb => cb.checked = true);
    updateCount();
}

function deselectAllGames() {
    document.querySelectorAll('.game-check').forEach(cb => cb.checked = false);
    updateCount();
}

function selectUnlinkedGames() {
    deselectAllGames();
    document.querySelectorAll('.game-item').forEach(item => {
        if (item.dataset.linked === '0') {
            item.querySelector('.game-check').checked = true;
        }
    });
    updateCount();
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.game-check').forEach(cb => {
        cb.addEventListener('change', updateCount);
    });
    document.getElementById('supplierSelect').addEventListener('change', updateCount);
    updateCount();
});
</script>
@endsection