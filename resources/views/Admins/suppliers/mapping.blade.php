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

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="text-muted small border-bottom">
                        <tr>
                            <th class="px-4">ID</th>
                            <th>GAME</th>
                            <th>NHÀ CUNG CẤP</th>
                            <th>MÃ GAME (SUPPLIER)</th>
                            <th>TRẠNG THÁI</th>
                            <th class="text-end px-4">THAO TÁC</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mappings as $mapping)
                            <tr>
                                <td class="px-4 fw-bold">#{{ $mapping->id }}</td>
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
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-plug fs-2 mb-3 opacity-50"></i>
                                    <p class="mb-0">Chưa có liên kết nào. <a href="{{ route('admin.suppliers.mapping.create') }}">Tạo liên kết đầu tiên</a></p>
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
@endsection