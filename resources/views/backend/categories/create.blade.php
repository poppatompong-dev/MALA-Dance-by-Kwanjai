@extends('backend.master')

@section('title', 'เพิ่มหมวดสินค้า')

@section('content')
<div class="card">
  <div class="card-body">
    <form action="{{ route('backend.admin.categories.store') }}" method="post" class="accountForm"
      enctype="multipart/form-data">
      @csrf
      <div class="card-body">
        <div class="row">
          <div class="mb-3 col-md-6">
            <label for="title" class="form-label">
              ชื่อหมวดสินค้า
              <span class="text-danger">*</span>
            </label>
            <input type="text" class="form-control" placeholder="เช่น หม่าล่าเสียบไม้" name="name"
              value="{{ old('name') }}" required>
          </div>
          <div class="mb-3 col-md-6">
            <label for="thumbnailInput" class="form-label">
              รูปหมวดสินค้า
            </label>
            <div class="image-upload-container" id="imageUploadContainer">
              <input type="file" class="form-control" name="category_image" id="thumbnailInput" accept="image/*" style="display: none;">
              <div class="thumb-preview" id="thumbPreviewContainer">
                <img src="{{ asset('assets/images/demo/no-image.svg') }}" alt="ตัวอย่างรูปหมวดสินค้า"
                  class="img-thumbnail d-none" id="thumbnailPreview">
                <div class="upload-text">
                  <i class="fas fa-plus-circle"></i>
                  <span>อัปโหลดรูป</span>
                </div>
              </div>
            </div>
          </div>

          <div class="mb-3 col-md-12">
            <label for="description" class="form-label">
              รายละเอียด
            </label>
            <textarea class="form-control" placeholder="รายละเอียดหมวดสินค้า" name="description">{{ old('description') }}</textarea>
          </div>
          <div class="mb-3 col-md-12">
            <div class="form-switch px-4">
              <input type="hidden" name="status" value="0">
              <input class="form-check-input" type="checkbox" name="status" id="active"
                value="1" checked>
              <label class="form-check-label" for="active">
                ใช้งาน
              </label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <button type="submit" class="btn bg-gradient-primary">บันทึกหมวดสินค้า</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
@push('script')
<script src="{{ asset('js/image-field.js') }}"></script>
@endpush
