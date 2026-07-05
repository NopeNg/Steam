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

    <!-- ======== TỔNG QUAN HỆ THỐNG ======== -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-6 col-md-4 col-xl">
                    <div class="border rounded-3 p-3 text-center h-100">
                        <div class="fs-1 text-primary mb-2"><i class="fas fa-dollar-sign"></i></div>
                        <h5 class="mb-0 fw-bold">{{ number_format($totalRevenue, 0, ',', '.') }}đ</h5>
                        <small class="text-muted">Tổng doanh thu</small>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl">
                    <div class="border rounded-3 p-3 text-center h-100">
                        <div class="fs-1 text-success mb-2"><i class="fas fa-shopping-bag"></i></div>
                        <h5 class="mb-0 fw-bold">{{ $totalOrders }}</h5>
                        <small class="text-muted">Tổng đơn hàng</small>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl">
                    <div class="border rounded-3 p-3 text-center h-100">
                        <div class="fs-1 text-warning mb-2"><i class="fas fa-users"></i></div>
                        <h5 class="mb-0 fw-bold">{{ $totalUsers }}</h5>
                        <small class="text-muted">Người dùng</small>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl">
                    <div class="border rounded-3 p-3 text-center h-100">
                        <div class="fs-1 text-danger mb-2"><i class="fas fa-exclamation-triangle"></i></div>
                        <h5 class="mb-0 fw-bold">{{ $errorKeys }}</h5>
                        <small class="text-muted">Key lỗi</small>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl">
                    <div class="border rounded-3 p-3 text-center h-100">
                        <div class="fs-1 text-secondary mb-2"><i class="fas fa-calendar-day"></i></div>
                        <h5 class="mb-0 fw-bold">{{ number_format($revenueMonth, 0, ',', '.') }}đ</h5>
                        <small class="text-muted">Doanh thu tháng này</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($errors->has('end_date'))
        <div class="alert alert-danger py-2 small">
            <i class="fas fa-exclamation-triangle"></i> {{ $errors->first('end_date') }}
        </div>
    @endif

    <!-- ======== DATE FILTER ======== -->
    <div class="card border-0 shadow-sm mb-4 rounded-3">
        <div class="card-body py-3">
            <form action="{{ route('admin.dashboard') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-auto">
                    <label class="form-label small fw-bold mb-0">Khoảng thời gian:</label>
                </div>
                <div class="col-auto">
                    <input type="date" class="form-control form-control-sm" name="start_date" value="{{ $startDate }}" required>
                </div>
                <div class="col-auto">
                    <span class="text-muted">→</span>
                </div>
                <div class="col-auto">
                    <input type="date" class="form-control form-control-sm" name="end_date" value="{{ $endDate }}" required>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-filter"></i> Lọc</button>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="quickSetDates(7)">7 ngày</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="quickSetDates(14)">14 ngày</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="quickSetDates(30)">30 ngày</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ======== TABS FOR CHARTS ======== -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header bg-transparent border-bottom-0 py-2">
            <ul class="nav nav-tabs card-header-tabs" id="chartTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="tab1-tab" data-bs-toggle="tab" data-bs-target="#tab1" type="button" role="tab">
                        <i class="fas fa-chart-line me-1"></i> Doanh thu
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab2-tab" data-bs-toggle="tab" data-bs-target="#tab2" type="button" role="tab">
                        <i class="fas fa-users me-1"></i> Người dùng
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab3-tab" data-bs-toggle="tab" data-bs-target="#tab3" type="button" role="tab">
                        <i class="fas fa-shopping-cart me-1"></i> Đơn hàng
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab4-tab" data-bs-toggle="tab" data-bs-target="#tab4" type="button" role="tab">
                        <i class="fas fa-credit-card me-1"></i> Thanh toán
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab5-tab" data-bs-toggle="tab" data-bs-target="#tab5" type="button" role="tab">
                        <i class="fas fa-trophy me-1"></i> Top Game
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <!-- Chart Type Switcher -->
            <div class="text-end mb-3" id="chartTypeControls">
                <span class="small text-muted me-2">Kiểu biểu đồ:</span>
                <button type="button" class="btn btn-sm btn-outline-primary chart-type-btn active" data-type="line"><i class="fas fa-chart-line"></i> Đường</button>
                <button type="button" class="btn btn-sm btn-outline-primary chart-type-btn" data-type="bar"><i class="fas fa-chart-bar"></i> Cột</button>
            </div>

            <div class="tab-content" id="chartTabsContent" style="min-height: 380px;">
                <!-- TAB 1: DOANH THU -->
                <div class="tab-pane fade show active" id="tab1" role="tabpanel">
                    <div style="height: 360px; width: 100%;">
                        <canvas id="chart1"></canvas>
                    </div>
                </div>
                <!-- TAB 2: NGƯỜI DÙNG -->
                <div class="tab-pane fade" id="tab2" role="tabpanel">
                    <div style="height: 360px; width: 100%;">
                        <canvas id="chart2"></canvas>
                    </div>
                </div>
                <!-- TAB 3: ĐƠN HÀNG -->
                <div class="tab-pane fade" id="tab3" role="tabpanel">
                    <div style="height: 360px; width: 100%;">
                        <canvas id="chart3"></canvas>
                    </div>
                </div>
                <!-- TAB 4: THANH TOÁN -->
                <div class="tab-pane fade" id="tab4" role="tabpanel">
                    <div class="row" style="height: 360px;">
                        <div class="col-md-6 h-100 position-relative">
                            <canvas id="chart4Pie" style="max-height: 340px;"></canvas>
                        </div>
                        <div class="col-md-6 h-100 position-relative">
                            <canvas id="chart4Bar" style="max-height: 340px;"></canvas>
                        </div>
                    </div>
                </div>
                <!-- TAB 5: TOP GAMES -->
                <div class="tab-pane fade" id="tab5" role="tabpanel">
                    <div class="row" style="height: 360px;">
                        <div class="col-md-6 h-100 position-relative">
                            <canvas id="chart5Bar" style="max-height: 340px;"></canvas>
                        </div>
                        <div class="col-md-6 h-100 position-relative">
                            <canvas id="chart5Doughnut" style="max-height: 340px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ======== STATS TABLE + TOP GAMES ======== -->
    <div class="row g-4">
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm mb-4 rounded-3 h-100">
                <div class="card-header py-3 bg-transparent border-bottom-0 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold">Đơn hàng vừa giao dịch</h5>
                    <a href="{{ route('admin.orders.index') }}" class="small text-decoration-none">Xem tất cả <i class="fas fa-arrow-right ms-1"></i></a>
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
                                        <td colspan="5" class="text-center py-4 text-muted">Chưa có đơn hàng nào.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm mb-4 rounded-3 h-100">
                <div class="card-header py-3 bg-transparent border-bottom-0">
                    <h5 class="card-title mb-0 fw-bold">Top Game bán chạy</h5>
                </div>
                <div class="card-body">
                    @forelse($topGames as $index => $topGame)
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-{{ $index < 1 ? 'primary' : 'secondary' }} text-white rounded p-2 me-3 fw-bold" style="width: 38px; text-align: center;">
                                #{{ $loop->iteration }}
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 fw-bold fs-6">{{ $topGame->game->name ?? 'Không xác định' }}</h6>
                                <small class="text-muted">{{ $topGame->total_sold }} mã Key đã xuất</small>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted small">Chưa có dữ liệu trong tuần này.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// ============ CHARTS DATA ============
const chart1Labels = @json($chart1Labels);
const chart1Revenue = @json($chart1Revenue);
const chart1Orders = @json($chart1Orders);

const chart2Labels = @json($chart2Labels);
const chart2NewUsers = @json($chart2NewUsers);
const chart2Cumulative = @json($chart2Cumulative);

const chart3Labels = @json($chart3Labels);
const chart3Completed = @json($chart3Completed);
const chart3Pending = @json($chart3Pending);
const chart3ApiError = @json($chart3ApiError);
const chart3Failed = @json($chart3Failed);

const chart4Labels = @json($chart4Labels);
const chart4Counts = @json($chart4Counts);
const chart4Revenues = @json($chart4Revenues);

const chart5Labels = @json($chart5Labels);
const chart5Sold = @json($chart5Sold);
const chart5Revenue = @json($chart5Revenue);

// Chart instances for switching type
let chart1, chart2, chart3, chart4Pie, chart4Bar, chart5Bar, chart5Doughnut;
let currentChartType = 'line';

const COLORS = {
    blue: 'rgba(13, 110, 253, 1)',
    blueBg: 'rgba(13, 110, 253, 0.15)',
    green: 'rgba(25, 135, 84, 1)',
    greenBg: 'rgba(25, 135, 84, 0.15)',
    orange: 'rgba(255, 193, 7, 1)',
    orangeBg: 'rgba(255, 193, 7, 0.15)',
    red: 'rgba(220, 53, 69, 1)',
    redBg: 'rgba(220, 53, 69, 0.15)',
    purple: 'rgba(111, 66, 193, 1)',
    purpleBg: 'rgba(111, 66, 193, 0.15)',
    cyan: 'rgba(13, 202, 240, 1)',
};

const palette = ['#0d6efd','#198754','#ffc107','#dc3545','#6f42c1','#0dcaf0','#fd7e14','#20c997','#6610f2','#d63384'];

function createGradient(ctx, color) {
    const g = ctx.createLinearGradient(0, 0, 0, 300);
    g.addColorStop(0, color.replace('1)', '0.4)'));
    g.addColorStop(1, color.replace('1)', '0.0)'));
    return g;
}

function buildChartConfig(type, labels, datasets, options = {}) {
    const isLine = type === 'line';
    const borderWidth = isLine ? 2 : 0;
    const fill = isLine;
    const tension = isLine ? 0.4 : 0;

    return {
        type: type,
        data: {
            labels: labels,
            datasets: datasets.map(d => ({
                ...d,
                fill: d.fill !== undefined ? d.fill : fill,
                tension: d.tension !== undefined ? d.tension : tension,
                borderWidth: d.borderWidth !== undefined ? d.borderWidth : borderWidth,
                borderRadius: type === 'bar' ? 4 : 0,
                maxBarThickness: type === 'bar' ? 60 : undefined,
            }))
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { boxWidth: 12, padding: 15, usePointStyle: true }
                },
                tooltip: {
                    callbacks: {
                        label: function(ctx) {
                            let val = ctx.parsed.y !== undefined ? ctx.parsed.y : ctx.parsed;
                            if (ctx.dataset.isCurrency) {
                                return ctx.dataset.label + ': ' + new Intl.NumberFormat('vi-VN').format(val) + 'đ';
                            }
                            return ctx.dataset.label + ': ' + val;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(150,150,150,0.08)', drawBorder: false },
                    ticks: {
                        callback: function(val) {
                            if (this.chart.data.datasets[0]?.isCurrency) {
                                return new Intl.NumberFormat('vi-VN', {notation: 'compact', compactDisplay: 'short'}).format(val);
                            }
                            return val;
                        }
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { maxRotation: 45 }
                }
            },
            ...options
        }
    };
}

function initCharts(type) {
    currentChartType = type;

    // CHART 1: Revenue
    const ctx1 = document.getElementById('chart1').getContext('2d');
    if (chart1) chart1.destroy();
    chart1 = new Chart(ctx1, buildChartConfig(type, chart1Labels, [
        {
            label: 'Doanh thu',
            data: chart1Revenue,
            borderColor: COLORS.blue,
            backgroundColor: type === 'line' ? createGradient(ctx1, COLORS.blue) : COLORS.blueBg,
            pointBackgroundColor: '#fff',
            pointBorderColor: COLORS.blue,
            pointRadius: 3,
            isCurrency: true,
        },
        {
            label: 'Số đơn hoàn thành',
            data: chart1Orders,
            borderColor: COLORS.green,
            backgroundColor: type === 'line' ? COLORS.greenBg : COLORS.greenBg,
            borderDash: type === 'line' ? [5, 5] : [],
            pointRadius: 0,
            yAxisID: 'y1',
            fill: false,
        }
    ], {
        scales: {
            y1: {
                beginAtZero: true,
                position: 'right',
                grid: { display: false },
                ticks: { callback: v => Number.isInteger(v) ? v : '' }
            }
        }
    }));

    // CHART 2: Users
    const ctx2 = document.getElementById('chart2').getContext('2d');
    if (chart2) chart2.destroy();
    chart2 = new Chart(ctx2, buildChartConfig(type, chart2Labels, [
        {
            label: 'Người dùng mới',
            data: chart2NewUsers,
            borderColor: COLORS.blue,
            backgroundColor: type === 'line' ? createGradient(ctx2, COLORS.blue) : COLORS.blue + '40',
            pointBackgroundColor: '#fff',
            pointBorderColor: COLORS.blue,
            pointRadius: 3,
        },
        {
            label: 'Tổng tích lũy',
            data: chart2Cumulative,
            borderColor: COLORS.green,
            backgroundColor: type === 'line' ? 'transparent' : COLORS.greenBg,
            borderDash: [5, 5],
            fill: false,
            pointRadius: 0,
            yAxisID: 'y1',
        }
    ], {
        scales: {
            y1: {
                beginAtZero: true,
                position: 'right',
                grid: { display: false },
                ticks: { callback: v => Number.isInteger(v) ? v : '' }
            }
        }
    }));

    // CHART 3: Orders stacked
    const ctx3 = document.getElementById('chart3').getContext('2d');
    if (chart3) chart3.destroy();
    if (type === 'bar') {
        chart3 = new Chart(ctx3, buildChartConfig('bar', chart3Labels, [
            { label: 'Hoàn thành', data: chart3Completed, backgroundColor: palette[1] + '80' },
            { label: 'Chờ thanh toán', data: chart3Pending, backgroundColor: palette[2] + '80' },
            { label: 'Lỗi API', data: chart3ApiError, backgroundColor: palette[3] + '80' },
            { label: 'Thất bại', data: chart3Failed, backgroundColor: palette[4] + '80' },
        ], {
            scales: {
                x: { stacked: true },
                y: { stacked: true, ticks: { callback: v => Number.isInteger(v) ? v : '' } }
            }
        }));
    } else {
        chart3 = new Chart(ctx3, buildChartConfig('line', chart3Labels, [
            { label: 'Hoàn thành', data: chart3Completed, borderColor: palette[1], backgroundColor: 'transparent', pointRadius: 2, tension: 0.3 },
            { label: 'Chờ thanh toán', data: chart3Pending, borderColor: palette[2], backgroundColor: 'transparent', pointRadius: 2, tension: 0.3 },
            { label: 'Lỗi API', data: chart3ApiError, borderColor: palette[3], backgroundColor: 'transparent', pointRadius: 2, tension: 0.3 },
            { label: 'Thất bại', data: chart3Failed, borderColor: palette[4], backgroundColor: 'transparent', pointRadius: 2, tension: 0.3 },
        ]));
    }

    // CHART 4: Payment (Pie + Bar)
    const ctx4Pie = document.getElementById('chart4Pie').getContext('2d');
    if (chart4Pie) chart4Pie.destroy();
    chart4Pie = new Chart(ctx4Pie, {
        type: 'doughnut',
        data: {
            labels: chart4Labels,
            datasets: [{
                data: chart4Counts,
                backgroundColor: palette.slice(0, chart4Labels.length),
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 12, padding: 10 } },
                title: { display: true, text: 'Số lượng giao dịch', font: { size: 14 } }
            }
        }
    });

    const ctx4Bar = document.getElementById('chart4Bar').getContext('2d');
    if (chart4Bar) chart4Bar.destroy();
    chart4Bar = new Chart(ctx4Bar, buildChartConfig('bar', chart4Labels, [
        {
            label: 'Doanh thu',
            data: chart4Revenues,
            backgroundColor: palette.slice(0, chart4Labels.length),
            isCurrency: true,
            fill: false,
        }
    ]));

    // CHART 5: Top Games
    const ctx5Bar = document.getElementById('chart5Bar').getContext('2d');
    if (chart5Bar) chart5Bar.destroy();
    chart5Bar = new Chart(ctx5Bar, buildChartConfig('bar', chart5Labels, [
        {
            label: 'Key đã bán',
            data: chart5Sold,
            backgroundColor: chart5Labels.map((_, i) => palette[i % palette.length] + '80'),
            borderColor: chart5Labels.map((_, i) => palette[i % palette.length]),
            borderWidth: 1,
            fill: false,
        }
    ]));

    const ctx5Doughnut = document.getElementById('chart5Doughnut').getContext('2d');
    if (chart5Doughnut) chart5Doughnut.destroy();
    chart5Doughnut = new Chart(ctx5Doughnut, {
        type: 'doughnut',
        data: {
            labels: chart5Labels,
            datasets: [{
                data: chart5Sold,
                backgroundColor: palette.slice(0, chart5Labels.length),
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 12, padding: 10, font: { size: 11 } } },
                title: { display: true, text: 'Tỉ lệ phân bố Game bán chạy', font: { size: 14 } }
            }
        }
    });
}

// ============ EVENT LISTENERS ============
document.addEventListener('DOMContentLoaded', function() {
    initCharts('line');

    document.querySelectorAll('.chart-type-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.chart-type-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            initCharts(this.dataset.type);
        });
    });
});

function quickSetDates(days) {
    const today = new Date();
    const past = new Date(today);
    past.setDate(past.getDate() - days + 1);
    document.querySelector('input[name="start_date"]').value = past.toISOString().split('T')[0];
    document.querySelector('input[name="end_date"]').value = today.toISOString().split('T')[0];
    document.querySelector('input[name="start_date"]').form.submit();
}
</script>
@endsection