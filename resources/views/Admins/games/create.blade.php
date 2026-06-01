@extends('Admins.layouts.admin')

@section('title', 'Thêm Mới Game - GameKey')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
        <h1 class="h3 fw-bold">Thêm Mới Sản Phẩm</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('admin.games.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
            </a>
        </div>
    </div>

    <form action="{{ route('admin.games.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="row g-4">
            <div class="col-12 col-lg-8">
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3 text-primary">Thông tin sản phẩm</h5>
                        
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Tên Game <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="publisher" class="form-label fw-bold">Nhà phát hành</label>
                                <input type="text" class="form-control" id="publisher" name="publisher">
                            </div>
                            <div class="col-md-6">
                                <label for="developer" class="form-label fw-bold">Nhà phát triển</label>
                                <input type="text" class="form-control" id="developer" name="developer">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label fw-bold">Mô tả chi tiết</label>
                            <textarea class="form-control" id="description" name="description" rows="5"></textarea>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3 text-primary">Cấu hình yêu cầu (Requirements)</h5>
                        <div class="mb-3">
                            <textarea class="form-control" id="requirements" name="requirements" rows="4"></textarea>
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
                                <option value="Active" selected>Đang bán (Active)</option>
                                <option value="Inactive">Tạm ẩn (Inactive)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="release_date" class="form-label fw-bold">Ngày phát hành</label>
                            <input type="date" class="form-control" id="release_date" name="release_date" value="{{ \Carbon\Carbon::today()->format('Y-m-d') }}">
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3 text-primary">Ảnh bìa (Bảng games)</h5>
                        <div class="mb-3">
                            <label for="cover_image" class="form-label fw-bold">Tải lên ảnh bìa chính <span class="text-danger">*</span></label>
                            <input class="form-control" type="file" id="cover_image" name="cover_image" accept="image/*" required>
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

                        <div id="image-upload-container">
                            <div class="row g-2 align-items-center mb-3 upload-row">
                                <div class="col-md-4">
                                    <input type="file" class="form-control" name="gallery_images[]" accept="image/*">
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select" name="gallery_types[]">
                                        <option value="Banner">Loại: Banner</option>
                                        <option value="Screenshot">Loại: Screenshot</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select" name="gallery_parts[]">
                                        <option value="Main Page">Vị trí: Main Page</option>
                                        <option value="Gameplay">Vị trí: Gameplay</option>
                                    </select>
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
            <button type="submit" class="btn btn-success px-5 fw-bold"><i class="fas fa-save me-1"></i> Xuất bản Game</button>
        </div>
    </form>
</div>

<script>
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