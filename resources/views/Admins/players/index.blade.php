@extends('Admins.layouts.admin')

@section('title', 'Quản lý Người dùng - GameKey')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
        <h1 class="h3 fw-bold">Quản lý Người dùng (Players)</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm mb-4 rounded-3">
        <div class="card-body">
            <form action="{{ route('admin.players.index') }}" method="GET" class="row g-3 align-items-center">
                <div class="col-md-9">
                    <input type="text" class="form-control" placeholder="Tìm theo Username hoặc Email..." name="search" value="{{ request('search') }}">
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
                            <th class="px-4" style="width: 80px;">ID</th>
                            <th>USERNAME</th>
                            <th>EMAIL</th>
                            <th>NGÀY THAM GIA</th>
                            <th>TRẠNG THÁI</th>
                            <th class="text-end px-4">THAO TÁC</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($players as $player)
                        <tr>
                            <td class="px-4 fw-bold">#{{ $player->id }}</td>
                            <td class="fw-bold text-info">{{ $player->username }}</td>
                            <td>{{ $player->email }}</td>
                            <td>{{ \Carbon\Carbon::parse($player->created_at)->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($player->status === 'Active')
                                    <span class="badge bg-success rounded-pill px-3">Hoạt động</span>
                                @else
                                    <span class="badge bg-danger rounded-pill px-3">Bì khóa</span>
                                @endif
                            </td>
                            <td class="text-end px-4">
                                <a href="{{ route('admin.players.revoked', $player->id) }}" class="btn btn-sm btn-outline-danger me-1 position-relative" title="Xem game đã thu hồi">
                                    <i class="fas fa-trash-alt me-1"></i> Đã thu hồi
                                </a>
                                <form action="{{ route('admin.players.toggle', $player->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    @if($player->status === 'Active')
                                        <button type="submit" class="btn btn-sm btn-outline-warning" onclick="return confirm('Bạn có chắc chắn muốn khóa tài khoản này?')">
                                            <i class="fas fa-user-slash me-1"></i> Khóa
                                        </button>
                                    @else
                                        <button type="submit" class="btn btn-sm btn-outline-success" onclick="return confirm('Bạn có chắc chắn muốn mở khóa tài khoản này?')">
                                            <i class="fas fa-user-check me-1"></i> Mở khóa
                                        </button>
                                    @endif
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-users-slash fs-2 mb-3 opacity-50"></i>
                                <p class="mb-0">Không tìm thấy người dùng nào phù hợp.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer border-0 py-3">
            <div class="d-flex justify-content-end">
                {{ $players->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection