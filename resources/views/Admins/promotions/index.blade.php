@extends('Admins.layouts.admin')

@section('title', 'Quản lý Khuyến mãi - GameKey')

@section('content')
    <div class="container-fluid py-4">
        <div
            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
            <h1 class="h3 fw-bold">Chiến dịch Khuyến mãi</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="{{ route('admin.promotions.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus me-1"></i> Tạo khuyến mãi mới
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card border-0 shadow-sm mb-4 rounded-3">
            <div class="card-body">
                <form action="{{ route('admin.promotions.index') }}" method="GET" class="row g-3 align-items-center">
                    <div class="col-md-9">
                        <input type="text" class="form-control" placeholder="Tìm theo tên chiến dịch..." name="search"
                            value="{{ request('search') }}">
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
                                <th>TÊN CHIẾN DỊCH</th>
                                <th class="text-center">MỨC GIẢM</th>
                                <th>THỜI GIAN BẮT ĐẦU</th>
                                <th>THỜI GIAN KẾT THÚC</th>
                                <th>TRẠNG THÁI</th>
                                <th class="text-end px-4">THAO TÁC</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($promotions as $promo)
                                <tr>
                                    <td class="px-4 fw-bold">#{{ $promo->id }}</td>
                                    <td class="fw-bold text-info">{{ $promo->campaign_name }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-danger fs-6">-{{ $promo->discount_percent }}%</span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($promo->start_time)->format('d/m/Y H:i') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($promo->end_time)->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @php
                                            $now = \Carbon\Carbon::now();
                                            $start = \Carbon\Carbon::parse($promo->start_time);
                                            $end = \Carbon\Carbon::parse($promo->end_time);
                                        @endphp

                                        @if($now->lt($start))
                                            <span class="badge bg-warning text-dark rounded-pill px-3">Sắp diễn ra</span>
                                        @elseif($now->gt($end))
                                            <span class="badge bg-secondary rounded-pill px-3">Đã kết thúc</span>
                                        @else
                                            <span class="badge bg-success rounded-pill px-3">Đang chạy</span>
                                        @endif
                                    </td>
                                    <td class="text-end px-4">
                                        <a href="{{ route('admin.promotions.edit', $promo->id) }}"
                                            class="btn btn-sm btn-outline-primary" title="Chỉnh sửa"><i
                                                class="fas fa-edit"></i></a>
                                        <form action="{{ route('admin.promotions.destroy', $promo->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa"
                                                onclick="return confirm('Bạn có chắc chắn muốn xóa chiến dịch này?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="fas fa-tags fs-2 mb-3 opacity-50"></i>
                                        <p class="mb-0">Không tìm thấy chiến dịch khuyến mãi nào.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer border-0 py-3">
                <div class="d-flex justify-content-end">
                    {{ $promotions->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection