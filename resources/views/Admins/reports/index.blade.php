@extends('Admins.layouts.admin')

@section('title', 'Báo cáo & Thống kê')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Báo cáo & Thống kê</h4>
    <div class="d-flex gap-2">
        <div class="dropdown">
            <button class="btn btn-outline-success btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="fas fa-file-csv me-1"></i>Xuất CSV
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('admin.reports.export', array_merge(['type' => 'orders'], request()->only(['start_date', 'end_date']))) }}"><i class="fas fa-shopping-cart me-2"></i>Đơn hàng</a></li>
                <li><a class="dropdown-item" href="{{ route('admin.reports.export', array_merge(['type' => 'customers'], request()->only(['start_date', 'end_date']))) }}"><i class="fas fa-users me-2"></i>Khách hàng</a></li>
                <li><a class="dropdown-item" href="{{ route('admin.reports.export', array_merge(['type' => 'revenue'], request()->only(['start_date', 'end_date']))) }}"><i class="fas fa-dollar-sign me-2"></i>Doanh thu</a></li>
                <li><a class="dropdown-item" href="{{ route('admin.reports.export', array_merge(['type' => 'top_games'], request()->only(['start_date', 'end_date']))) }}"><i class="fas fa-gamepad me-2"></i>Top game</a></li>
                <li><a class="dropdown-item" href="{{ route('admin.reports.export', array_merge(['type' => 'inventory'], request()->only(['start_date', 'end_date']))) }}"><i class="fas fa-key me-2"></i>Kho hàng</a></li>
                <li><a class="dropdown-item" href="{{ route('admin.reports.export', array_merge(['type' => 'vip_customers'], request()->only(['start_date', 'end_date']))) }}"><i class="fas fa-crown me-2"></i>Top khách hàng</a></li>
            </ul>
        </div>
    </div>
</div>

<form method="GET" action="{{ route('admin.reports.index') }}" class="row g-2 mb-4 align-items-end">
    <div class="col-auto">
        <label class="form-label fw-bold">Từ ngày</label>
        <input type="date" name="start_date" class="form-control form-control-sm"
            value="{{ $startDate }}">
    </div>
    <div class="col-auto">
        <label class="form-label fw-bold">Đến ngày</label>
        <input type="date" name="end_date" class="form-control form-control-sm"
            value="{{ $endDate }}">
    </div>
    <input type="hidden" name="tab" value="{{ $tab }}">
    <div class="col-auto">
        <button type="submit" class="btn btn-primary btn-sm">
            <i class="fas fa-filter me-1"></i>Lọc
        </button>
        <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary btn-sm">Mặc định</a>
    </div>
</form>

<ul class="nav nav-tabs mb-4">
    <li class="nav-item">
        <a class="nav-link {{ $tab === 'revenue' ? 'active' : '' }}"
            href="?{{ http_build_query(array_merge(request()->query(), ['tab' => 'revenue'])) }}">
            <i class="fas fa-dollar-sign me-1"></i>Doanh thu & Đơn hàng
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $tab === 'customers' ? 'active' : '' }}"
            href="?{{ http_build_query(array_merge(request()->query(), ['tab' => 'customers'])) }}">
            <i class="fas fa-users me-1"></i>Khách hàng
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $tab === 'inventory' ? 'active' : '' }}"
            href="?{{ http_build_query(array_merge(request()->query(), ['tab' => 'inventory'])) }}">
            <i class="fas fa-boxes-stacked me-1"></i>Kho hàng Key
        </a>
    </li>
</ul>

@if($tab === 'revenue')
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body">
                <div class="text-primary mb-1"><i class="fas fa-dollar-sign fa-2x"></i></div>
                <h6 class="text-muted">Doanh thu thực tế</h6>
                <h4 class="fw-bold">{{ number_format($netRevenue, 0, ',', '.') }} VNĐ</h4>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body">
                <div class="text-success mb-1"><i class="fas fa-shopping-cart fa-2x"></i></div>
                <h6 class="text-muted">Tổng đơn hàng</h6>
                <h4 class="fw-bold">{{ $totalOrders }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body">
                <div class="text-info mb-1"><i class="fas fa-check-circle fa-2x"></i></div>
                <h6 class="text-muted">Đơn hoàn thành</h6>
                <h4 class="fw-bold">{{ $completedOrders }}</h4>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <span class="badge bg-success fs-6">Hoàn thành: {{ $completedOrders }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <span class="badge bg-warning text-dark fs-6">Chờ xử lý: {{ $pendingOrders }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <span class="badge bg-danger fs-6">Thất bại: {{ $cancelledOrders }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <span class="badge bg-secondary fs-6">lỗi Key: {{ $apiErrorOrders }}</span>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header fw-bold">
                <i class="fas fa-chart-line me-2"></i>Doanh thu theo ngày
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="100"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header fw-bold">
                <i class="fas fa-chart-pie me-2"></i>Trạng thái đơn hàng
            </div>
            <div class="card-body">
                <canvas id="statusChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header fw-bold">
                <i class="fas fa-credit-card me-2"></i>Phương thức thanh toán
            </div>
            <div class="card-body">
                @if(count($paymentLabels) > 0)
                <canvas id="paymentChart" height="150"></canvas>
                @else
                <p class="text-muted text-center">Chưa có dữ liệu</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header fw-bold">
                <i class="fas fa-gamepad me-2"></i>Top game bán chạy
            </div>
            <div class="card-body" style="max-height: 350px; overflow-y: auto;">
                <table class="table table-hover table-sm mb-0">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Game</th>
                            <th style="width: 80px;">Đã bán</th>
                            <th style="width: 120px;">Doanh thu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topGamesRevenue as $idx => $game)
                        <tr>
                            <td>{{ $idx + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @php
                                        $imgSrc = $game->game_image;
                                        if ($imgSrc && !str_starts_with($imgSrc, 'http')) {
                                            $imgSrc = asset('storage/' . $imgSrc);
                                        }
                                    @endphp
                                    @if($imgSrc)
                                    <img src="{{ $imgSrc }}" 
                                         alt="{{ $game->game_name }}" 
                                         style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                    @else
                                    <div style="width: 40px; height: 40px; background: #e9ecef; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-gamepad text-muted"></i>
                                    </div>
                                    @endif
                                    <span class="fw-bold">{{ $game->game_name }}</span>
                                </div>
                            </td>
                            <td><span class="badge bg-primary">{{ $game->total_sold }}</span></td>
                            <td class="text-center">{{ number_format($game->total_revenue, 0, ',', '.') }} VNĐ</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-muted text-center">Chưa có dữ liệu</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header fw-bold">
                <i class="fas fa-chart-bar me-2"></i>Số đơn hàng theo ngày
            </div>
            <div class="card-body">
                <canvas id="orderCountChart" height="80"></canvas>
            </div>
        </div>
    </div>
</div>

@elseif($tab === 'customers')
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body">
                <div class="text-primary mb-1"><i class="fas fa-user-plus fa-2x"></i></div>
                <h6 class="text-muted">Khách hàng mới</h6>
                <h4 class="fw-bold">{{ $newUsersCount }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body">
                <div class="text-info mb-1"><i class="fas fa-user-check fa-2x"></i></div>
                <h6 class="text-muted">Đơn từ khách mới</h6>
                <h4 class="fw-bold">{{ $newCustomerOrders }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body">
                <div class="text-success mb-1"><i class="fas fa-user-friends fa-2x"></i></div>
                <h6 class="text-muted">Đơn từ khách cũ</h6>
                <h4 class="fw-bold">{{ $returningCustomerOrders }}</h4>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header fw-bold">
                <i class="fas fa-user-plus me-2"></i>Khách hàng mới theo ngày
            </div>
            <div class="card-body">
                <canvas id="newUsersChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header fw-bold">
                <i class="fas fa-crown me-2"></i>Top khách hàng
            </div>
            <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                <table class="table table-hover table-sm mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Số đơn</th>
                            <th>Tổng chi tiêu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topCustomers as $idx => $c)
                        <tr>
                            <td>{{ $idx + 1 }}</td>
                            <td>{{ $c->username }}</td>
                            <td>{{ $c->email }}</td>
                            <td>{{ $c->total_orders }}</td>
                            <td>{{ number_format($c->total_spent, 0, ',', '.') }} VNĐ</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-muted text-center">Chưa có dữ liệu</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@elseif($tab === 'inventory')
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body">
                <div class="text-secondary mb-1"><i class="fas fa-key fa-2x"></i></div>
                <h6 class="text-muted">Tổng key</h6>
                <h4 class="fw-bold">{{ $totalKeys }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body">
                <div class="text-danger mb-1"><i class="fas fa-exclamation-triangle fa-2x"></i></div>
                <h6 class="text-muted">Key đã thu hồi</h6>
                <h4 class="fw-bold">{{ $errorKeys }}</h4>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header fw-bold">
                <i class="fas fa-link me-2 text-success"></i>Game đã liên kết NCC: <span class="badge bg-success">{{ $linkedGames->count() }}</span>
            </div>
            <div class="card-body" style="max-height: 350px; overflow-y: auto;">
                <table class="table table-hover table-sm mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tên Game</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($linkedGames as $idx => $game)
                        <tr>
                            <td>{{ $idx + 1 }}</td>
                            <td>{{ $game->name }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-muted text-center">Chưa có game nào được liên kết</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header fw-bold">
                <i class="fas fa-unlink me-2 text-danger"></i>Game chưa liên kết NCC: <span class="badge bg-danger">{{ $unlinkedGames->count() }}</span>
            </div>
            <div class="card-body" style="max-height: 350px; overflow-y: auto;">
                <table class="table table-hover table-sm mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tên Game</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($unlinkedGames as $idx => $game)
                        <tr>
                            <td>{{ $idx + 1 }}</td>
                            <td>{{ $game->name }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-muted text-center">Tất cả game đều đã được liên kết</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
    const gridColor = isDark ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)';
    const textColor = isDark ? '#ccc' : '#666';
    Chart.defaults.color = textColor;
    Chart.defaults.borderColor = gridColor;

    @if($tab === 'revenue')
    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: @json($chartRevenueLabels),
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: @json($chartRevenueData),
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78,115,223,0.1)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: true } },
            scales: {
                y: { beginAtZero: true, grid: { color: gridColor } },
                x: { grid: { display: false } }
            }
        }
    });

    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: @json($orderStatusLabels),
            datasets: [{
                data: @json($orderStatusData),
                backgroundColor: ['#1cc88a', '#f6c23e', '#858796', '#e74a3b']
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } }
        }
    });

    @if(count($paymentLabels) > 0)
    new Chart(document.getElementById('paymentChart'), {
        type: 'bar',
        data: {
            labels: @json($paymentLabels),
            datasets: [{
                label: 'Số đơn',
                data: @json($paymentCounts),
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b']
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: gridColor } },
                x: { grid: { display: false } }
            }
        }
    });
    @endif

    new Chart(document.getElementById('orderCountChart'), {
        type: 'bar',
        data: {
            labels: @json($chartRevenueLabels),
            datasets: [{
                label: 'Số đơn',
                data: @json($chartOrderCountData),
                backgroundColor: '#1cc88a'
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: gridColor } },
                x: { grid: { display: false } }
            }
        }
    });
    @endif

    @if($tab === 'customers')
    new Chart(document.getElementById('newUsersChart'), {
        type: 'line',
        data: {
            labels: @json($chartNewUsersLabels),
            datasets: [{
                label: 'Khách mới',
                data: @json($chartNewUsersData),
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78,115,223,0.1)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: true } },
            scales: {
                y: { beginAtZero: true, grid: { color: gridColor } },
                x: { grid: { display: false } }
            }
        }
    });
    @endif
</script>
@endsection
