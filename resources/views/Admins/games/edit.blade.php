
@extends('Admins.layouts.admin')

@section('title', 'Chỉnh Sửa Game - GameKey')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
        <h1 class="h3 fw-bold">Chỉnh Sửa Game</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('admin.games.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
            </a>
        </div>
    </div>

    <form action="{{ route('admin.games.update', $game->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="row g-4">
            <div class="col-12 col-lg-8">
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3 text-primary">Thông tin sản phẩm</h5>
                        
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Tên Game <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $game->name }}" required>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="publisher" class="form-label fw-bold">Nhà phát hành</label>
                                <input type="text" class="form-control" id="publisher" name="publisher" value="{{ $game->publisher }}">
                            </div>
                            <div class="col-md-6">
                                <label for="developer" class="form-label fw-bold">Nhà phát triển</label>
                                <input type="text" class="form-control" id="developer" name="developer" value="{{ $game->developer }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label fw-bold">Mô tả chi tiết</label>
                            <textarea class="form-control" id="description" name="description" rows="5">{{ $game->description }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3 text-primary">Cấu hình yêu cầu (Requirements)</h5>
                        <div class="mb-3">
                            <textarea class="form-control" id="requirements" name="requirements" rows="4">{{ $game->requirements }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3 text-primary">Phân loại & Trạng thái</h5>
                        
                        <div class="mb-3">
                            <label for="status" class="form-label fw-bold">Trạng thái hoạt động</label>
                            <select class="form-select" id="status" name="status">
                                <option value="Active" {{ $game->status == 'Active' ? 'selected' : '' }}>Đang bán (Active)</option>
                                <option value="Inactive" {{ $game->status == 'Inactive' ? 'selected' : '' }}>Tạm ẩn (Inactive)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="release_date" class="form-label fw-bold">Ngày phát hành</label>
                            <input type="date" class="form-control" id="release_date" name="release_date" value="{{ \Carbon\Carbon::parse($game->release_date)->format('Y-m-d') }}">
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3 text-primary">Ảnh bìa (Bảng games)</h5>
                        <div class="mb-3">
                            <label for="cover_image" class="form-label fw-bold">Tải lên ảnh bìa chính</label>
                            <input class="form-control" type="file" id="cover_image" name="cover_image" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label for="cover_image_url" class="form-label fw-bold">Hoặc nhập URL ảnh bìa</label>
                            <input type="text" class="form-control" id="cover_image_url" name="cover_image_url" placeholder="https://example.com/image.jpg" value="{{ old('cover_image_url') }}">
                        </div>
                        <div class="border rounded-3 p-3 text-center bg-light">
                            <img src="{{ $game->cover_image }}" class="img-fluid rounded shadow-sm mb-2" style="max-height: 150px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4 text-primary">Bộ sưu tập ảnh (Bảng game_images)</h5>
                        <div class="row g-4 mb-4">
                            @foreach($game->images as $img)
                            <div class="col-md-4 col-lg-3">
                                <div class="card h-100 border shadow-sm">
                                    <div class="position-relative">
                                        <img src="{{ $img->image_path }}" class="card-img-top" style="height: 140px; object-fit: cover;">
                                    </div>
                                    <div class="card-body p-2 text-center">
                                        <button type="button" class="btn btn-sm btn-outline-danger w-100" onclick="deleteImage({{ $img->id }}, this)">
                                            <i class="fas fa-trash me-1"></i> Xóa ảnh này
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <hr class="text-muted my-4">
                        <h6 class="fw-bold mb-3">Tải lên ảnh phụ mới</h6>
                        <div id="image-upload-container">
                            <div class="row g-2 align-items-center mb-3 upload-row">
                                <div class="col-md-5">
                                    <input type="file" class="form-control" name="gallery_images[]" accept="image/*">
                                </div>
                                <div class="col-md-5">
                                    <input type="text" class="form-control" name="gallery_urls[]" placeholder="Hoặc nhập URL ảnh (https://...)">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger remove-row-btn" disabled><i class="fas fa-times"></i></button>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-success mt-2" id="add-more-image-btn">
                            <i class="fas fa-plus me-1"></i> Thêm hàng tải ảnh
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-end gap-2 mt-4 mb-5">
            <a href="{{ route('admin.games.index') }}" class="btn btn-secondary px-4">Hủy bỏ</a>
            <button type="submit" class="btn btn-success px-5 fw-bold"><i class="fas fa-save me-1"></i> Lưu toàn bộ thay đổi</button>
        </div>
    </form>
</div>

    <script>
    function deleteImage(imageId, button) {
        if (!confirm('Bạn có chắc muốn xóa ảnh này?')) return;

        fetch('/admin/games/images/' + imageId, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            }
        })
        .then(response => {
            if (response.redirected) {
                window.location.reload();
                return;
            }
            if (!response.ok) {
                throw new Error('Lỗi khi xóa ảnh');
            }
            // Xóa card ảnh khỏi giao diện
            const card = button.closest('.col-md-4');
            if (card) card.remove();
        })
        .catch(error => {
            alert('Lỗi: ' + error.message);
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('image-upload-container');
        const addBtn = document.getElementById('add-more-image-btn');

        addBtn.addEventListener('click', function() {
            const firstRow = container.querySelector('.upload-row');
            const newRow = firstRow.cloneNode(true);
            
            newRow.querySelector('input[type="file"]').value = '';
            newRow.querySelector('.remove-row-btn').disabled = false;
            
            newRow.querySelector('.remove-row-btn').addEventListener('click', function() {
                newRow.remove();
            });
            
            container.appendChild(newRow);
        });
    });
</script>
@endsection