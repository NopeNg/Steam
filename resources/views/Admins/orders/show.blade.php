@extends('Admins.layouts.admin')

@section('title', 'Chi tiết đơn hàng - GameKey')

@section('content')
    @php
        // Tính tổng số tiền đã refund
        $refundedTotal = 0;
        $allOrderKeys = collect();
        if (isset($order->orderItems)) {
            $allOrderKeys = $order->orderItems->flatMap(function($item) {
                return $item->gameKeys;
            });
            foreach ($allOrderKeys as $k) {
                if (isset($k->supplier_transaction_id) && str_starts_with($k->supplier_transaction_id, 'REFUNDED:')) {
                    $refundedTotal += ($k->orderItem->price_at_purchase ?? 0);
                }
            }
        }
    @endphp
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
        
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
                <i class="fas fa-exclamation-circle me-1"></i> {{ $errors->first() }}
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

                <div class="card border-0 shadow-sm rounded-3 mb-4">
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
                                    <option value="API_Error" {{ $order->status == 'API_Error' ? 'selected' : '' }}>lỗi Key (Key Error)</option>
                                    <option value="Failed" {{ $order->status == 'Failed' ? 'selected' : '' }}>Thất bại (Failed)</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 fw-bold btn-sm mb-2">
                                <i class="fas fa-save me-1"></i> Cập nhật trạng thái
                            </button>
                        </form>

                        @if($order->status == 'API_Error')
                        <hr class="border-secondary">
                        <form action="{{ route('admin.orders.refund', $order->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success w-100 fw-bold btn-sm" onclick="return confirm('Xác nhận hoàn tiền toàn bộ đơn hàng #ORD-{{ $order->id }}? Số tiền {{ number_format($order->total_amount, 0, ',', '.') }}đ sẽ được hoàn vào ví người chơi.')">
                                <i class="fas fa-undo me-1"></i> Hoàn tiền toàn bộ đơn hàng
                            </button>
                        </form>
                        @endif

                    </div>
                </div>

                {{-- Quản lý key --}}
                @if(in_array($order->status, ['Completed', 'API_Error']))
                    @if($allOrderKeys->isNotEmpty())
                        <div class="card border-0 shadow-sm rounded-3">
                            <div class="card-body p-4">
                                <h5 class="fw-bold mb-3"><i class="fas fa-key me-2 text-info"></i>Danh sách Key trong đơn</h5>
                                @php
                                    // Chia key thành 2 nhóm: chưa refund và đã refund
                                    $activeKeys = $allOrderKeys->filter(function($k) {
                                        return !str_starts_with($k->supplier_transaction_id ?? '', 'REFUNDED:');
                                    });
                                    $refundedKeys = $allOrderKeys->filter(function($k) {
                                        return str_starts_with($k->supplier_transaction_id ?? '', 'REFUNDED:');
                                    });
                                @endphp

                                @foreach($activeKeys as $key)
                                @php
                                    $isReplaced = str_starts_with($key->supplier_transaction_id ?? '', 'REPLACED:');
                                @endphp
                                <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-dark rounded border border-gray-700">
                                    <div>
                                        <small class="text-muted d-block">Key: <span class="text-info">{{ $key->key_code }}</span></small>
                                        @if($key->orderItem->gameVersion && $key->orderItem->gameVersion->game)
                                            <small class="text-muted">{{ $key->orderItem->gameVersion->game->name }} ({{ $key->orderItem->gameVersion->version_name }})</small>
                                        @endif
                                        @if($isReplaced)
                                            <br><small class="text-warning"><i class="fas fa-exchange-alt me-1"></i>Đã được đổi key</small>
                                        @endif
                                        @if($key->status === 'Revoked' && !$isReplaced && !str_starts_with($key->supplier_transaction_id ?? '', 'REFUNDED:'))
                                            <br><small class="text-danger"><i class="fas fa-ban me-1"></i>Đã thu hồi</small>
                                        @endif
                                    </div>
                                    <div class="d-flex gap-1">
                                        @if($order->status == 'API_Error')
                                        <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#replaceKeyModal{{ $key->id }}">
                                            <i class="fas fa-exchange-alt me-1"></i> Đổi key
                                        </button>
                                        @if(!$isReplaced && $key->status !== 'Revoked')
                                        <button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#refundKeyModal{{ $key->id }}">
                                            <i class="fas fa-undo me-1"></i> Hoàn tiền
                                        </button>
                                        @endif
                                        @endif
                                        @if($order->status != 'API_Error' && $key->status !== 'Revoked')
                                        <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#revokeKeyModal{{ $key->id }}">
                                            <i class="fas fa-trash-alt me-1"></i> Thu hồi
                                        </button>
                                        @endif
                                    </div>
                                </div>

                                    {{-- Modal nhập lý do thu hồi --}}
                                    <div class="modal fade" id="revokeKeyModal{{ $key->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content bg-dark text-white border-secondary">
                                                <div class="modal-header border-secondary">
                                                    <h5 class="modal-title fw-bold text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Thu hồi Key</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('admin.keys.revoke', $key->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <p class="text-warning small mb-3">
                                                            <i class="fas fa-info-circle me-1"></i> 
                                                            Thu hồi key: <strong class="text-info">{{ $key->key_code }}</strong>
                                                        </p>
                                                        <div class="mb-3">
                                                            <label class="form-label small text-muted fw-bold">Lý do thu hồi <span class="text-danger">*</span></label>
                                                            <textarea name="revoke_reason" class="form-control bg-transparent text-white border-secondary" rows="3" required placeholder="Nhập lý do thu hồi key này..."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-secondary">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                                        <button type="submit" class="btn btn-danger fw-bold">Xác nhận thu hồi</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Modal đổi key mới --}}
                                    <div class="modal fade" id="replaceKeyModal{{ $key->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content bg-dark text-white border-secondary">
                                                <div class="modal-header border-secondary">
                                                    <h5 class="modal-title fw-bold text-info"><i class="fas fa-exchange-alt me-2"></i>Đổi Key mới</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('admin.keys.replace', $key->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <p class="text-warning small mb-3">
                                                            <i class="fas fa-info-circle me-1"></i> 
                                                            Key cũ: <strong class="text-info">{{ $key->key_code }}</strong>
                                                        </p>
                                                        <div class="mb-3">
                                                            <label class="form-label small text-muted fw-bold">Mã key mới <span class="text-danger">*</span></label>
                                                            <input type="text" name="new_key_code" class="form-control bg-transparent text-white border-secondary" required placeholder="Nhập mã key mới">
                                                        </div>
                                                        <div class="p-3 rounded-3 bg-info bg-opacity-10 border border-info">
                                                            <small class="text-info">
                                                                <i class="fas fa-info-circle me-1"></i>
                                                                Key cũ sẽ bị vô hiệu hóa và key mới sẽ được thay thế vào thư viện người chơi.
                                                            </small>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-secondary">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                                        <button type="submit" class="btn btn-info fw-bold text-white">Xác nhận đổi key</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Modal xác nhận hoàn tiền --}}
                                    <div class="modal fade" id="refundKeyModal{{ $key->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content bg-dark text-white border-secondary">
                                                <div class="modal-header border-secondary">
                                                    <h5 class="modal-title fw-bold text-success"><i class="fas fa-undo me-2"></i>Hoàn tiền Key</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('admin.keys.refund', $key->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <p class="text-warning small mb-3">
                                                            <i class="fas fa-info-circle me-1"></i> 
                                                            Hoàn tiền cho key: <strong class="text-info">{{ $key->key_code }}</strong>
                                                        </p>
                                                        @if($key->orderItem)
                                                            @php
                                                                $refundAmount = $key->orderItem->price_at_purchase ;
                                                            @endphp
                                                            <p class="small mb-2">Số tiền hoàn: <strong class="text-success">{{ number_format($refundAmount, 0, ',', '.') }}đ</strong></p>
                                                        @endif
                                                        <div class="p-3 rounded-3 bg-warning bg-opacity-10 border border-warning">
                                                            <small class="text-warning">
                                                                <i class="fas fa-exclamation-triangle me-1"></i>
                                                                Key sẽ bị thu hồi và tiền sẽ được hoàn vào ví người chơi.
                                                            </small>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-secondary">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                                        <button type="submit" class="btn btn-success fw-bold">Xác nhận hoàn tiền</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                {{-- Hiển thị key đã refund --}}
                                @if($refundedKeys->isNotEmpty())
                                <hr class="border-secondary">
                                <h6 class="text-muted mb-2"><i class="fas fa-undo me-1"></i>Key đã hoàn tiền</h6>
                                @foreach($refundedKeys as $key)
                                <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-dark bg-opacity-50 rounded border border-danger border-opacity-25">
                                    <div>
                                        <small class="text-muted d-block">Key: <s class="text-muted">{{ $key->key_code }}</s> <span class="badge bg-danger ms-1">Đã hoàn tiền</span></small>
                                        @if($key->orderItem->gameVersion && $key->orderItem->gameVersion->game)
                                            <small class="text-muted">{{ $key->orderItem->gameVersion->game->name }} ({{ $key->orderItem->gameVersion->version_name }})</small>
                                        @endif
                                    </div>
                                    <div>
                                        <small class="text-danger">{{ number_format($key->orderItem->price_at_purchase ?? 0, 0, ',', '.') }}đ</small>
                                    </div>
                                </div>
                                @endforeach
                                @endif
                            </div>
                        </div>
                    @endif
                @endif
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
                        @if($refundedTotal > 0)
                        <div class="d-flex justify-content-end align-items-center gap-3 mt-1">
                            <span class="small text-danger">(Đã hoàn tiền {{ number_format($refundedTotal, 0, ',', '.') }}đ)</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($order->status == 'API_Error')
    <div class="modal fade" id="manualKeyModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white border-secondary">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title fw-bold">Nhập Key Thủ Công</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.keys.replace', 0) }}" method="POST">
                    @csrf
                    <input type="hidden" name="new_key_code" value="">
                    <div class="modal-body">
                        <div class="p-3 rounded-3 bg-warning bg-opacity-10 border border-warning">
                            <small class="text-warning">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                Chức năng này đã được thay thế. Vui lòng sử dụng nút <strong>"Đổi key"</strong> cho từng key cụ thể trong danh sách bên trên.
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer border-secondary">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endsection