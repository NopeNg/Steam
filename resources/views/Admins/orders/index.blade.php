@extends('Admins.layouts.admin')

@section('title', 'Quản lý Đơn hàng - GameKey')

@section('content')
    <div class="container-fluid py-4">
        <div
            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
            <h1 class="h3 fw-bold">Danh sách Đơn hàng</h1>
        </div>

        <div class="card border-0 shadow-sm mb-4 rounded-3">
            <div class="card-body">
                <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-3 align-items-center">
                    <div class="col-md-5">
                        <input type="text" class="form-control" placeholder="Tìm theo Mã đơn, Username, Email..."
                            name="search" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" name="status">
                            <option value="">Tất cả trạng thái</option>
                            <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Hoàn thành
                                (Completed)</option>
                            <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Chờ thanh toán
                                (Pending)</option>
                            <option value="API_Error" {{ request('status') == 'API_Error' ? 'selected' : '' }}>lỗi Key (Key
                                Error)</option>
                            <option value="Failed" {{ request('status') == 'Failed' ? 'selected' : '' }}>Thất bại (Failed)
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-filter me-1"></i> Lọc dữ liệu
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
                                <th class="px-4">MÃ ĐƠN</th>
                                <th>KHÁCH HÀNG</th>
                                <th>NGÀY TẠO</th>
                                <th>TỔNG TIỀN</th>
                                <th>PHƯƠNG THỨC</th>
                                <th>TRẠNG THÁI</th>
                                <th class="text-end px-4">THAO TÁC</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td class="px-4 fw-bold text-primary">#ORD-{{ $order->id }}</td>
                                    <td>
                                        @if($order->player)
                                            <h6 class="mb-0 fw-bold">{{ $order->player->username }}</h6>
                                            <small class="text-muted">{{ $order->player->email }}</small>
                                        @else
                                            <span class="text-muted fst-italic">Khách vô danh</span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}</td>
                                    <td class="fw-bold text-danger">{{ number_format($order->total_amount, 0, ',', '.') }}đ</td>
                                    <td>
                                        <span
                                            class="badge bg-light text-dark border">{{ $order->payment_method ?? 'Chưa rõ' }}</span>
                                    </td>
                                    <td>
                                        @if($order->status == 'Completed')
                                            <span class="badge bg-success rounded-pill px-3">Hoàn thành</span>
                                        @elseif($order->status == 'Pending')
                                            <span class="badge bg-warning text-dark rounded-pill px-3">Đang chờ</span>
                                        @elseif($order->status == 'API_Error')
                                            <span class="badge bg-danger rounded-pill px-3">lỗi Key</span>
                                        @else
                                            <span class="badge bg-secondary rounded-pill px-3">Thất bại</span>
                                        @endif
                                    </td>
                                    <td class="text-end px-4">
                                        <a href="{{ route('admin.orders.show', $order->id) }}"
                                            class="btn btn-sm btn-info text-white" title="Xem chi tiết đơn hàng">
                                            <i class="fas fa-file-invoice"></i> Chi tiết
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="fas fa-inbox fs-2 mb-3 opacity-50"></i>
                                        <p class="mb-0">Không tìm thấy đơn hàng nào.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer border-0 py-3">
                <div class="d-flex justify-content-end">
                    {{ $orders->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection