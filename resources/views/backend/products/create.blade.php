@extends('backend.master')

@section('title', 'เพิ่มสินค้า')

@section('content')
<div class="card">
  <div class="card-body">
    <form action="{{ route('backend.admin.products.store') }}" method="post" class="accountForm"
      enctype="multipart/form-data">
      @csrf
      <div class="card-body">
        <div class="row">
          <div class="mb-3 col-md-6">
            <label for="title" class="form-label">
              ชื่อสินค้า
              <span class="text-danger">*</span>
            </label>
            <input type="text" class="form-control" placeholder="เช่น หมูสามชั้นหม่าล่า" name="name"
              value="{{ old('name') }}" required>
          </div>
          <div class="mb-3 col-md-6">
            <label for="sku" class="form-label">
              SKU
              <span class="text-danger">*</span>
            </label>
            <input type="text" class="form-control" placeholder="เช่น MALA-PORK-BELLY" name="sku"
              value="{{ old('sku') }}" required>
          </div>
          <div class="mb-3 col-md-6">
            <label for="brand_id" class="form-label">
              แบรนด์/แหล่งสินค้า
              <span class="text-danger">*</span>
            </label>
            <select class="form-control select2" style="width: 100%;" name="brand_id" required>
              <option value="">เลือกแบรนด์</option>
              @foreach ($brands as $item)
              <option value={{ $item->id }}
                {{ old('brand_id') == $item->id ? 'selected' : '' }}>
                {{ $item->name }}
              </option>
              @endforeach
            </select>
          </div>
          <div class="mb-3 col-md-6">
            <label for="category_id" class="form-label">
              หมวดสินค้า
              <span class="text-danger">*</span>
            </label>
            <select class="form-control select2" style="width: 100%;" name="category_id" required>
              <option value="">เลือกหมวดสินค้า</option>
              @foreach ($categories as $item)
              <option value={{ $item->id }}
                {{ old('category_id') == $item->id ? 'selected' : '' }}>
                {{ $item->name }}
              </option>
              @endforeach
            </select>
          </div>
          <div class="mb-3 col-md-6">
            <label for="price" class="form-label">
              ราคาขาย
              <span class="text-danger">*</span>
            </label>
            <input type="number" step="0.01" min="0" class="form-control"
              placeholder="กรอกราคาขาย" name="price" value="{{ old('price') }}" required>
          </div>
          <!-- <div class="mb-3 col-md-6">
          <label for="quantity" class="form-label">
            สต็อกเริ่มต้น
            <span class="text-danger">*</span>
          </label>
          <input type="number" class="form-control" placeholder="กรอกจำนวนสินค้า" name="quantity"
            value="{{ old('quantity') }}" required>
        </div> -->
          <div class="mb-3 col-md-6">
            <label for="unit_id" class="form-label">
              หน่วยนับ
              <span class="text-danger">*</span>
            </label>
            <select class="form-control" style="width: 100%;" name="unit_id" required>
              <option value="">เลือกหน่วยนับ</option>
              @foreach ($units as $item)
              <option value={{ $item->id }}
                {{ old(key: 'unit_id') == $item->id ? 'selected' : '' }}>
                {{ $item->title . ' (' . $item->short_name . ')' }}
              </option>
              @endforeach
            </select>
          </div>
          <div class="mb-3 col-md-6">
            <label for="discount_type" class="form-label">
              ประเภทส่วนลด
            </label>
            <select class="form-control form-select" name="discount_type">
              <option value="">เลือกประเภทส่วนลด</option>
              <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>
                ลดเป็นจำนวนเงิน
              </option>
              <option value="percentage"
                {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>
                ลดเป็นเปอร์เซ็นต์
              </option>
            </select>
          </div>
          <div class="mb-3 col-md-6">
            <label for="purchase_price" class="form-label">
              ราคาซื้อ
              <span class="text-danger">*</span>
            </label>
            <input type="number" step="0.01" min="0" class="form-control"
              placeholder="กรอกราคาซื้อ" name="purchase_price" value="{{ old('purchase_price') }}" required>
          </div>
          <div class="mb-3 col-md-6">
            <label for="discount_value" class="form-label">
              มูลค่าส่วนลด
            </label>
            <input type="number" step="0.01" min="0" class="form-control"
              placeholder="กรอกส่วนลด" name="discount" value="{{ old('discount') }}">
          </div>
          <div class="mb-3 col-md-6">
            <label for="thumbnailInput" class="form-label">
              รูปสินค้า
            </label>
            <div class="image-upload-container" id="imageUploadContainer">
              <input type="file" class="form-control" name="product_image" id="thumbnailInput" accept="image/*" style="display: none;">
              <div class="thumb-preview" id="thumbPreviewContainer">
                <img src="{{ asset('assets/images/demo/no-image.svg') }}" alt="ตัวอย่างรูปสินค้า"
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
            <textarea class="form-control" placeholder="เช่น วัตถุดิบพร้อมย่าง รสหม่าล่าเผ็ดหอม" name="description">{{ old('description') }}</textarea>
          </div>

          <div class="mb-3 col-md-6">
            <label for="expire_date" class="form-label">
              วันหมดอายุ
            </label>
            <div class="input-group date" id="reservationdate" data-target-input="nearest">
              <input type="text" placeholder="เลือกวันหมดอายุสินค้า" class="form-control datetimepicker-input" data-target="#reservationdate" name="expire_date" value="{{ old('expire_date') }}" />
              <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
              </div>
            </div>
          </div>
          <div class="mb-3 col-md-12">
            <div class="form-switch px-4">
              <input type="hidden" name="status" value="0">
              <input class="form-check-input" type="checkbox" name="status" id="active"
                value="1" checked>
              <label class="form-check-label" for="active">
                เปิดขาย
              </label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <button type="submit" class="btn bg-gradient-primary">บันทึกสินค้า</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@push('style')
<style>
  .select2-container--default .select2-selection--single {
    height: calc(1.5em + 0.75rem + 2px) !important;
  }
</style>

@endpush
@push('script')
<script src="{{ asset('js/image-field.js') }}"></script>
<script>
  $(function() {
    //Date picker
    $('#reservationdate').datetimepicker({
      format: 'YYYY-MM-DD'
    });
  })
</script>
@endpush
