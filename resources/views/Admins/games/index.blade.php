@extends('Admins.layouts.admin')

@section('title', 'Quản lý Sản phẩm - GameKey')

@section('content')
    <div class="container-fluid py-4">
        <div
            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
            <h1 class="h3 fw-bold">Danh sách Sản phẩm</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="{{ route('admin.games.create') }}" class="btn btn-sm btn-primary shadow-sm">
                    <i class="fas fa-plus me-1"></i> Thêm Game mới
                </a>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4 rounded-3">
            <div class="card-body">
                <form action="{{ route('admin.games.index') }}" method="GET" class="row g-3 align-items-center">
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="Tìm kiếm tên game..." name="search"
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-search me-1"></i> Lọc
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
                                <th class="px-4" style="width: 50px;">ID</th>
                                <th style="width: 80px;">ẢNH</th>
                                <th>TÊN GAME</th>
                                <th>NHÀ PHÁT HÀNH</th>
                                <th>TRẠNG THÁI</th>
                                <th class="text-end px-4">THAO TÁC</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($games as $game)
                                <tr>
                                    <td class="px-4 fw-bold">{{ $game->id }}</td>
                                    <td>
                                        <img src="{{ $game->cover_image }}" alt="{{ $game->name }}"
                                            class="img-fluid rounded shadow-sm"
                                            style="height: 60px; width: 45px; object-fit: cover;">
                                    </td>
                                    <td>
                                        <h6 class="mb-0 fw-bold">{{ $game->name }}</h6>
                                        <small class="text-muted">Phát hành:
                                            {{ \Carbon\Carbon::parse($game->release_date)->format('d/m/Y') }}</small>
                                    </td>
                                    <td>{{ $game->publisher }}</td>
                                    <td>
                                        <span class="badge {{ $game->status == 'Active' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $game->status == 'Active' ? 'Đang bán' : 'Tạm ẩn' }}
                                        </span>
                                    </td>
                                    <td class="text-end px-4">
                                        <a href="{{ route('admin.games.show', $game->id) }}" class="btn btn-sm btn-outline-info"
                                            title="Xem chi tiết"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('admin.games.edit', $game->id) }}"
                                            class="btn btn-sm btn-outline-primary" title="Chỉnh sửa"><i
                                                class="fas fa-edit"></i></a>
                                        <form action="{{ route('admin.games.destroy', $game->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa"
                                                onclick="return confirm('Bạn có chắc chắn muốn xóa game này?')"><i
                                                    class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Chưa có sản phẩm nào trong hệ thống.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer border-0 py-3">
                <div class="d-flex justify-content-end">
                    {{ $games->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection