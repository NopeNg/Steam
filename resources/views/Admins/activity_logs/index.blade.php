@extends('Admins.layouts.admin')

@section('title', 'Lịch sử hoạt động')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="fas fa-history me-2"></i>Lịch sử hoạt động</h4>
</div>

<!-- Search & Filters -->
<form method="GET" action="{{ route('admin.activity-logs.index') }}" class="row g-2 mb-4">
    <div class="col-md-3">
        <div class="input-group input-group-sm">
            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm hành động..."
                value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="fas fa-search"></i>
            </button>
            @if(request('search') || request('start_date') || request('end_date'))
            <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-times"></i>
            </a>
            @endif
        </div>
    </div>
    <div class="col-auto">
        <label class="visually-hidden">Từ ngày</label>
        <input type="date" name="start_date" class="form-control form-control-sm"
            value="{{ request('start_date') }}" title="Từ ngày">
    </div>
    <div class="col-auto">
        <label class="visually-hidden">Đến ngày</label>
        <input type="date" name="end_date" class="form-control form-control-sm"
            value="{{ request('end_date') }}" title="Đến ngày">
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-outline-primary btn-sm">
            <i class="fas fa-filter me-1"></i>Lọc
        </button>
    </div>
</form>

<!-- Logs Table -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">ID</th>
                        <th style="width: 250px;">Thời gian</th>
                        <th>Hành động</th>
                        <th>Chi tiết</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td class="text-muted">{{ $log->id }}</td>
                        <td class="text-nowrap">
                            <span class="badge bg-secondary-subtle text-secondary-emphasis border">
                                <i class="far fa-clock me-1"></i>{{ $log->created_at->format('d/m/Y H:i:s') }}
                            </span>
                        </td>
                        <td>
                            <span class="fw-medium">{{ $log->action }}</span>
                        </td>
                        <td>
                            @if($log->details)
                            <small class="text-muted">{{ $log->details }}</small>
                            @else
                            <span class="text-muted fst-italic">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4">
                            <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">Chưa có hoạt động nào được ghi nhận.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($logs->hasPages())
    <div class="card-footer">
        <div class="d-flex justify-content-center">
            {{ $logs->links() }}
        </div>
    </div>
    @endif
</div>
@endsection