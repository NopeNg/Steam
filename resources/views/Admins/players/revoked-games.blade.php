@extends('Admins.layouts.admin')

@section('title', 'Game đã thu hồi - ' . $player->username . ' - GameKey')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
        <div>
            <h1 class="h3 fw-bold">
                <i class="fas fa-trash-alt me-2 text-danger"></i>Game đã thu hồi
            </h1>
            <p class="text-muted mb-0">
                Người dùng: <strong class="text-info">{{ $player->username }}</strong> 
                <span class="mx-2">|</span> 
                Email: <strong>{{ $player->email }}</strong>
                <span class="mx-2">|</span>
                Tổng số: <strong class="text-danger">{{ $revokedKeys->count() }}</strong> game bị thu hồi
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.players.index') }}" class="btn btn-sm btn-outline-secondary shadow-sm">
                <i class="fas fa-arrow-left me-1"></i> Quay lại
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($revokedKeys->isEmpty())
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body text-center py-5">
                <i class="fas fa-check-circle text-success fs-1 mb-3 opacity-50"></i>
                <p class="text-muted fs-5 mb-0">Người dùng này không có game nào bị thu hồi.</p>
            </div>
        </div>
    @else
        <div class="row g-4">
            @foreach($revokedKeys as $key)
            <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                <div class="card border-0 shadow-sm rounded-3 h-100">
                    <!-- Cover Image -->
                    <div class="position-relative" style="padding-top: 100%; background: #1a1a2e;">
                        @if($key->cover_image)
                            <img src="{{ asset($key->cover_image) }}" 
                                 class="position-absolute top-0 start-0 w-100 h-100 rounded-top"
                                 style="object-fit: cover;"
                                 alt="{{ $key->game_name }}">
                        @else
                            <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center rounded-top" 
                                 style="background: linear-gradient(135deg, #1a1a2e, #16213e);">
                                <i class="fas fa-gamepad text-secondary" style="font-size: 3rem; opacity: 0.3;"></i>
                            </div>
                        @endif
                        <!-- Badge Revoked -->
                        <span class="position-absolute top-0 end-0 badge bg-danger m-2">
                            <i class="fas fa-ban me-1"></i>Đã thu hồi
                        </span>
                    </div>
                    
                    <!-- Game Info -->
                    <div class="card-body p-3 d-flex flex-column">
                        <h6 class="fw-bold mb-1 text-truncate" title="{{ $key->game_name }}">{{ $key->game_name }}</h6>
                        <small class="text-muted mb-2">Phiên bản: {{ $key->version_name ?? 'N/A' }}</small>
                        
                        @if($key->key_code)
                            <small class="text-muted mb-2">
                                <span class="text-info">Key:</span> 
                                <code class="small">{{ $key->key_code }}</code>
                            </small>
                        @endif
                        
                        <!-- Nút xem lý do -->
                        <button type="button" class="btn btn-sm btn-outline-danger mt-auto w-100" 
                                onclick="openReasonModal('{{ $key->id }}')">
                            <i class="fas fa-info-circle me-1"></i> Xem lý do
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Modal xem lý do thu hồi -->
@foreach($revokedKeys as $key)
<div class="modal fade" id="reasonModal{{ $key->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: #1e1e2f; border: 1px solid #333;">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-exclamation-triangle text-danger me-2"></i>Lý do thu hồi
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex align-items-center mb-3">
                    @if($key->cover_image)
                        <img src="{{ asset($key->cover_image) }}" class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;" alt="">
                    @else
                        <div class="rounded me-3 d-flex align-items-center justify-content-center" 
                             style="width: 60px; height: 60px; background: #2a2a3e;">
                            <i class="fas fa-gamepad text-secondary"></i>
                        </div>
                    @endif
                    <div>
                        <h6 class="mb-0 fw-bold">{{ $key->game_name }}</h6>
                        <small class="text-muted">Key: <code>{{ $key->key_code }}</code></small>
                    </div>
                </div>
                
                <div class="p-3 rounded-3" style="background: #2a2a3e; border-left: 3px solid #dc3545;">
                    @if($key->revoke_reason)
                        <p class="mb-0 text-light">{{ $key->revoke_reason }}</p>
                    @else
                        <p class="mb-0 text-muted fst-italic">Không có lý do cụ thể.</p>
                    @endif
                </div>
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection

@section('scripts')
<script>
function openReasonModal(id) {
    const modal = new bootstrap.Modal(document.getElementById('reasonModal' + id));
    modal.show();
}
</script>
@endsection