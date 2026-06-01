@extends('Admins.layouts.admin')

@section('title', 'Bảng điều khiển - GameKey')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
        <h1 class="h3 fw-bold">Bảng điều khiển hệ thống</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-primary shadow-sm">
                <i class="fas fa-sync-alt me-1"></i> Làm mới dữ liệu
            </a>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 bg-primary text-white rounded-3">
                <div class="card-body pb-2">
                    <div class="d-flex justify-content-between mb-2">
                        <div>
                            <p class="text-uppercase fw-bold mb-1 opacity-75 small">Doanh thu tháng này</p>
                            <h3 class="mb-0 fw-bold">{{ number_format($revenueMonth, 0, ',', '.') }}đ</h3>
                        </div>
                        <div class="fs-1 opacity-50"><i class="fas fa-wallet"></i></div>
                    </div>
                    <hr class="border-light opacity-25 my-2">
                    <div class="d-flex justify-content-between text-white small">
                        <span class="opacity-75">Hôm nay:</span>
                        <span class="fw-bold">{{ number_format($revenueToday, 0, ',', '.') }}đ</span>
                    </div>
                    <div class="d-flex justify-content-between text-white small mt-1">
                        <span class="opacity-75">Tuần này:</span>
                        <span class="fw-bold">{{ number_format($revenueWeek, 0, ',', '.') }}đ</span>
                    </div>
                    <div class="d-flex justify-content-between text-white small mt-1">
                        <span class="opacity-75">Năm nay:</span>
                        <span class="fw-bold">{{ number_format($revenueYear, 0, ',', '.') }}đ</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 rounded-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-uppercase fw-bold mb-1 text-muted small">Đơn hàng mới (24h)</p>
                            <h3 class="mb-0 fw-bold">{{ $orders24h }}</h3>
                        </div>
                        <div class="fs-1 text-info opacity-50"><i class="fas fa-shopping-cart"></i></div>
                    </div>
                    <div class="mt-3 fs-6">
                        <span class="text-success fw-bold small"><i class="fas fa-check-circle"></i> {{ $orders24hSuccess }} Thành công</span>
                        <span class="text-muted small"> ({{ $orders24hPending }} Đang xử lý)</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 rounded-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-uppercase fw-bold mb-1 text-muted small">Người dùng mới (24h)</p>
                            <h3 class="mb-0 fw-bold">{{ $newUsers }}</h3>
                        </div>
                        <div class="fs-1 text-success opacity-50"><i class="fas fa-users"></i></div>
                    </div>
                    <div class="mt-3 fs-6">
                        <span class="text-primary fw-bold small">{{ number_format($totalUsers, 0, ',', '.') }}</span>
                        <span class="text-muted small"> Tổng số</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 {{ $apiErrors > 0 ? 'bg-danger' : 'bg-success' }} text-white rounded-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-uppercase fw-bold mb-1 opacity-75 small">Lỗi kéo API Key</p>
                            <h3 class="mb-0 fw-bold">{{ $apiErrors }}</h3>
                        </div>
                        <div class="fs-1 opacity-50">
                            <i class="fas {{ $apiErrors > 0 ? 'fa-exclamation-triangle' : 'fa-check-circle' }}"></i>
                        </div>
                    </div>
                    <div class="mt-3 fs-6">
                        <span class="fw-bold small">Trạng thái: </span>
                        <span class="opacity-75 small">
                            {{ $apiErrors > 0 ? 'Cần Admin đối soát gấp' : 'Hệ thống chạy ổn định' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header py-3 bg-transparent d-flex justify-content-between align-items-center flex-wrap">
                    <h5 class="card-title mb-0 fw-bold">Biểu đồ Doanh thu</h5>
                    <form action="{{ route('admin.dashboard') }}" method="GET" class="d-flex gap-2 align-items-center mt-2 mt-md-0">
                        <input type="date" class="form-control form-control-sm" name="start_date" value="{{ $startDate }}" required>
                        <span class="text-muted">-</span>
                        <input type="date" class="form-control form-control-sm" name="end_date" value="{{ $endDate }}" required>
                        <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-filter"></i> Lọc</button>
                    </form>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" style="height: 300px; width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm mb-4 rounded-3 h-100">
                <div class="card-header py-3 border-bottom-0">
                    <h5 class="card-title mb-0 fw-bold">Đơn hàng vừa giao dịch</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="text-muted small border-bottom">
                                <tr>
                                    <th class="px-4">MÃ ĐƠN</th>
                                    <th>KHÁCH HÀNG</th>
                                    <th>THỜI GIAN</th>
                                    <th>TỔNG TIỀN</th>
                                    <th>TRẠNG THÁI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders as $order)
                                <tr>
                                    <td class="px-4 fw-bold text-primary">#ORD-{{ $order->id }}</td>
                                    <td class="fw-bold">{{ $order->player->username ?? 'Khách vô danh' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}</td>
                                    <td class="fw-bold">{{ number_format($order->total_amount, 0, ',', '.') }}đ</td>
                                    <td>
                                        @if($order->status == 'Completed')
                                            <span class="badge bg-success rounded-pill px-3">Hoàn thành</span>
                                        @elseif($order->status == 'Pending')
                                            <span class="badge bg-warning text-dark rounded-pill px-3">Chờ thanh toán</span>
                                        @elseif($order->status == 'API_Error')
                                            <span class="badge bg-danger rounded-pill px-3">Lỗi API</span>
                                        @else
                                            <span class="badge bg-secondary rounded-pill px-3">Hủy bỏ</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Chưa có đơn hàng nào trong hệ thống.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer border-0 py-3 text-center">
                    <a href="{{ route('admin.orders.index') }}" class="text-decoration-none small fw-bold">Xem tất cả <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm mb-4 rounded-3 h-100">
                <div class="card-header py-3 border-bottom-0">
                    <h5 class="card-title mb-0 fw-bold">Thao tác & Thống kê nhanh</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2 mb-4">
                        <a href="{{ route('admin.keys.index') }}" class="btn btn-primary">
                            <i class="fas fa-gift me-2"></i> Tạo Key Giveaway mới
                        </a>
                        <a href="{{ route('admin.promotions.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-bullhorn me-2"></i> Cài đặt Khuyến mãi (Flash Sale)
                        </a>
                    </div>

                    <hr class="text-muted">

                    <h6 class="fw-bold mb-3 mt-2 text-muted">Top Game bán chạy tuần này</h6>
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary text-white rounded p-2 me-3 fw-bold">#1</div>
                        <div class="flex-grow-1">
                            <h6 class="mb-0 fw-bold fs-6">Black Myth: Wukong</h6>
                            <small class="text-muted">45 mã Key đã xuất</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="bg-secondary text-white rounded p-2 me-3 fw-bold">#2</div>
                        <div class="flex-grow-1">
                            <h6 class="mb-0 fw-bold fs-6">Counter-Strike 2</h6>
                            <small class="text-muted">32 mã Key đã xuất</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const chartDates = @json($chartDates);
        const chartRevenues = @json($chartRevenues);

        let gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(13, 110, 253, 0.5)');
        gradient.addColorStop(1, 'rgba(13, 110, 253, 0.0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartDates,
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: chartRevenues,
                    borderColor: '#0d6efd',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#0d6efd',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('vi-VN').format(context.parsed.y) + 'đ';
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(150, 150, 150, 0.1)',
                            drawBorder: false
                        },
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('vi-VN', { notation: "compact", compactDisplay: "short" }).format(value);
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    });
</script>
@endsection