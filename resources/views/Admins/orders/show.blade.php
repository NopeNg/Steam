@extends('Admins.layouts.admin')

@section('title', 'Chi tiết đơn hàng - GameKey')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
        <h1 class="h3 fw-bold">Chi tiết đơn hàng #ORD-{{ $order->id }}</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4 mb-4">
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-primary mb-3"><i class="fas fa-user me-2"></i>Khách hàng</h5>
                    @if($order->player)
                        <div class="mb-1 fw-bold fs-6 text-info">{{ $order->player->username }}</div>
                        <div class="text-muted small mb-2">{{ $order->player->email }}</div>
                        <div class="small">Trạng thái tài khoản: <span class="badge bg-success">{{ $order->player->status }}</span></div>
                    @else
                        <span class="text-muted fst-italic">Tài khoản đã bị xóa khỏi hệ thống</span>
                    @endif
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-primary mb-3"><i class="fas fa-credit-card me-2"></i>Thanh toán</h5>
                    <div class="row g-2 small">
                        <div class="col-6 text-muted">Phương thức:</div>
                        <div class="col-6 fw-bold text-end">{{ $order->payment_method ?? 'Chưa rõ' }}</div>
                        
                        <div class="col-6 text-muted">Loại giao dịch:</div>
                        <div class="col-6 text-end">{{ $order->order_type ?? 'Mua Key' }}</div>
                        
                        <div class="col-6 text-muted">Thời gian tạo:</div>
                        <div class="col-6 text-end">{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i:s') }}</div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-primary mb-3"><i class="fas fa-cogs me-2"></i>Xử lý đơn hàng</h5>
                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="status" class="form-label small text-muted fw-bold">Trạng thái đơn</label>
                            <select class="form-select" id="status" name="status">
                                <option value="Completed" {{ $order->status == 'Completed' ? 'selected' : '' }}>Hoàn thành (Completed)</option>
                                <option value="Pending" {{ $order->status == 'Pending' ? 'selected' : '' }}>Chờ thanh toán (Pending)</option>
                                <option value="API_Error" {{ $order->status == 'API_Error' ? 'selected' : '' }}>Lỗi API (API Error)</option>
                                <option value="Failed" {{ $order->status == 'Failed' ? 'selected' : '' }}>Thất bại (Failed)</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-bold btn-sm">
                            <i class="fas fa-save me-1"></i> Cập nhật trạng thái
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header py-3 bg-transparent border-0">
                    <h5 class="card-title mb-0 fw-bold text-primary"><i class="fas fa-shopping-basket me-2"></i>Sản phẩm trong đơn</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="text-muted small border-bottom">
                                <tr>
                                    <th class="px-4">TÊN SẢN PHẨM / PHIÊN BẢN</th>
                                    <th class="text-center">SỐ LƯỢNG</th>
                                    <th class="text-end">ĐƠN GIÁ</th>
                                    <th class="text-end px-4">THÀNH TIỀN</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($order->orderItems as $item)
                                <tr>
                                    <td class="px-4">
                                        @if($item->gameVersion && $item->gameVersion->game)
                                            <h6 class="mb-0 fw-bold">{{ $item->gameVersion->game->name }}</h6>
                                            <small class="text-info">Phiên bản: {{ $item->gameVersion->version_name }}</small>
                                        @else
                                            <span class="text-muted fst-italic">Sản phẩm không còn tồn tại</span>
                                        @endif
                                    </td>
                                    <td class="text-center fw-bold">{{ $item->quantity ?? 1 }}</td>
                                    <td class="text-end">{{ number_format($item->price_at_purchase, 0, ',', '.') }}đ</td>
                                    <td class="text-end px-4 fw-bold text-danger">
                                        {{ number_format(($item->price_at_purchase * ($item->quantity ?? 1)), 0, ',', '.') }}đ
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">Không tìm thấy chi tiết sản phẩm.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 p-4">
                    <div class="d-flex justify-content-end align-items-center gap-3">
                        <span class="fs-5 text-muted">Tổng cộng thanh toán:</span>
                        <span class="fs-3 fw-bold text-danger">{{ number_format($order->total_amount, 0, ',', '.') }}đ</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection