@extends('Admins.layouts.admin')

@section('title', 'Quản lý Key & Đối soát - GameKey')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
        <h1 class="h3 fw-bold">Quản lý Kho Key & Đối soát API</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('admin.keys.custom.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus me-1"></i> Tạo Key Giveaway phát quà
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
            <form action="{{ route('admin.keys.index') }}" method="GET" class="row g-3 align-items-center">
                <div class="col-md-5">
                    <input type="text" class="form-control" placeholder="Tìm theo mã Key hoặc tên Game..." name="search" value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <select class="form-select" name="status">
                        <option value="">Tất cả trạng thái kho</option>
                        <option value="Available" {{ request('status') == 'Available' ? 'selected' : '' }}>Sẵn có (Available)</option>
                        <option value="Sold" {{ request('status') == 'Sold' ? 'selected' : '' }}>Đã bán (Sold)</option>
                        <option value="Giveaway" {{ request('status') == 'Giveaway' ? 'selected' : '' }}>Sự kiện (Giveaway)</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-search me-1"></i> Lọc dữ liệu
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
                            <th>MÃ KEY GAME</th>
                            <th>SẢN PHẨM / PHIÊN BẢN (ĐỐI SOÁT ĐƠN)</th>
                            <th>TRẠNG THÁI</th>
                            <th class="text-end px-4">ĐỐI SOÁT HỆ THỐNG</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($keys as $key)
                        <tr>
                            <td class="px-4 fw-bold">#{{ $key->id }}</td>
                            <td class="fw-bold text-info">{{ $key->key_code }}</td>
                            <td>
                                @if($key->orderItem && $key->orderItem->gameVersion && $key->orderItem->gameVersion->game)
                                    <h6 class="mb-0 fw-bold small">{{ $key->orderItem->gameVersion->game->name }}</h6>
                                    <small class="text-muted">Đơn: #ORD-{{ $key->orderItem->order_id }} ({{ $key->orderItem->gameVersion->version_name }})</small>
                                @else
                                    <span class="text-muted fst-italic small">{{ $key->supplier_transaction_id ?? 'Key Kho / Chưa xuất' }}</span>
                                @endif
                            </td>
                            <td>
                                @if($key->status == 'Available')
                                    <span class="badge bg-success rounded-pill px-3">Sẵn có</span>
                                @elseif($key->status == 'Sold')
                                    <span class="badge bg-secondary rounded-pill px-3">Đã bán</span>
                                @else
                                    <span class="badge rounded-pill px-3 text-white" style="background-color: #6f42c1;">Giveaway</span>
                                @endif
                            </td>
                            <td class="text-end px-4">
                                @if($key->order_item_id)
                                    <span class="badge bg-light text-success border border-success-subtle px-2 py-1 small">
                                        <i class="fas fa-check-circle me-1"></i> Khớp API Đơn Hàng
                                    </span>
                                @else
                                    <span class="badge bg-light text-warning border border-warning-subtle px-2 py-1 small">
                                        <i class="fas fa-info-circle me-1"></i> Key Tĩnh / Sự Kiện
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fas fa-key fs-2 mb-3 opacity-50"></i>
                                <p class="mb-0">Không tìm thấy mã điều phối Key nào.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer border-0 py-3">
            <div class="d-flex justify-content-end">
                {{ $keys->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createKeyModal" tabindex="-1" aria-labelledby="createKeyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow shadow-lg">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold" id="createKeyModalLabel">Phát Hành Key Sự Kiện (Giveaway)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.keys.custom.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="key_code" class="form-label fw-bold small">Nhập chuỗi mã Key game</label>
                        <input type="text" class="form-control" id="key_code" name="key_code" required placeholder="Ví dụ: A1B2-C3D4-E5F6-G7H8">
                    </div>
                    <div class="mb-3">
                        <label for="note" class="form-label fw-bold small">Ghi chú chiến dịch / Tên Game phát tặng</label>
                        <input type="text" class="form-control" id="note" name="note" placeholder="Ví dụ: Tặng CS2 - Event 2026">
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-bold px-4">Kích hoạt & Đưa vào kho</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection