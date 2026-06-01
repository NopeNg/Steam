@extends('Admins.layouts.admin')

@section('title', 'Quản lý Nhà cung cấp Key - GameKey')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
        <h1 class="h3 fw-bold">Quản lý Nhà cung cấp Key</h1>
        <div class="btn-toolbar mb-2 mb-md-0 d-flex gap-2">
            <a href="{{ route('admin.suppliers.mapping') }}" class="btn btn-sm btn-outline-info">
                <i class="fas fa-link me-1"></i> Liên kết Game
            </a>
            <a href="{{ route('admin.suppliers.create') }}" class="btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-plus me-1"></i> Thêm nhà cung cấp
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
            <i class="fas fa-exclamation-circle me-1"></i> {{ $errors->first() }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm mb-4 rounded-3">
        <div class="card-body">
            <form action="{{ route('admin.suppliers.index') }}" method="GET" class="row g-3 align-items-center">
                <div class="col-md-9">
                    <input type="text" class="form-control" placeholder="Tìm theo tên, mã code hoặc URL..." name="search" value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-search me-1"></i> Tìm kiếm
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="text-muted small border-bottom">
                        <tr>
                            <th class="px-4">ID</th>
                            <th>TÊN NHÀ CUNG CẤP</th>
                            <th>MÃ CODE</th>
                            <th>URL</th>
                            <th>PRIORITY</th>
                            <th>LIÊN KẾT GAME</th>
                            <th>TRẠNG THÁI</th>
                            <th class="text-end px-4">THAO TÁC</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suppliers as $supplier)
                            <tr>
                                <td class="px-4 fw-bold">#{{ $supplier->id }}</td>
                                <td class="fw-bold text-info">{{ $supplier->name }}</td>
                                <td><code>{{ $supplier->code }}</code></td>
                                <td class="small text-muted" style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    {{ $supplier->base_url }}
                                </td>
                                <td>
                                    <span class="badge bg-{{ $supplier->priority >= 100 ? 'primary' : 'secondary' }}">
                                        {{ $supplier->priority }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $supplier->gameMappings->count() }} game
                                    </span>
                                </td>
                                <td>
                                    @if($supplier->status == 'Active')
                                        <span class="badge bg-success rounded-pill px-3">Hoạt động</span>
                                    @else
                                        <span class="badge bg-secondary rounded-pill px-3">Tạm ngưng</span>
                                    @endif
                                </td>
                                <td class="text-end px-4">
                                    <form action="{{ route('admin.suppliers.healthCheck', $supplier->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success" title="Kiểm tra kết nối">
                                            <i class="fas fa-heartbeat"></i>
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.suppliers.edit', $supplier->id) }}" class="btn btn-sm btn-outline-primary" title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.suppliers.toggle', $supplier->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-outline-{{ $supplier->status == 'Active' ? 'warning' : 'success' }}"
                                            title="{{ $supplier->status == 'Active' ? 'Vô hiệu hóa' : 'Kích hoạt' }}">
                                            <i class="fas fa-{{ $supplier->status == 'Active' ? 'pause' : 'play' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.suppliers.destroy', $supplier->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa"
                                            onclick="return confirm('Xóa nhà cung cấp này? Game liên kết sẽ không bị ảnh hưởng.')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="fas fa-plug fs-2 mb-3 opacity-50"></i>
                                    <p class="mb-0">Chưa có nhà cung cấp nào. <a href="{{ route('admin.suppliers.create') }}">Thêm nhà cung cấp đầu tiên</a></p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer border-0 py-3">
            <div class="d-flex justify-content-end">
                {{ $suppliers->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection