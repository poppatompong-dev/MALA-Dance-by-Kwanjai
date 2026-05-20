@extends('backend.master')

@section('title', 'นำเข้ารายการสินค้า')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title mb-0">อัปโหลดไฟล์สินค้า</h2>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>นำเข้าไม่ได้:</strong> {{ $errors->first() }}
                        </div>
                    @endif

                    <form action="{{ route('backend.admin.products.import') }}" method="post" class="accountForm" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="productImportFile">ไฟล์รายการสินค้า</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="file" id="productImportFile" accept=".xlsx,.xls,.csv,.txt" required>
                                    <label class="custom-file-label" for="productImportFile">เลือกไฟล์ Excel หรือ CSV</label>
                                </div>
                                <div class="input-group-append">
                                    <a class="input-group-text" href="{{ route('backend.admin.products.import', ['download-template' => true]) }}">
                                        <i class="fas fa-download mr-1"></i> ดาวน์โหลดเทมเพลต
                                    </a>
                                </div>
                            </div>
                            <small class="form-text text-muted">
                                รองรับไฟล์ .xlsx, .xls, .csv และ .txt ขนาดไม่เกิน 5 MB
                            </small>
                        </div>

                        <button type="submit" class="btn bg-gradient-primary">
                            <i class="fas fa-file-import mr-1"></i> นำเข้าสินค้า
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title mb-0">รูปแบบคอลัมน์</h2>
                </div>
                <div class="card-body">
                    <p class="mb-2">ไฟล์นำเข้าต้องมีหัวคอลัมน์ตามเทมเพลต:</p>
                    <ul class="pl-3 mb-3">
                        <li><code>name</code>, <code>sku</code>, <code>description</code></li>
                        <li><code>category</code>, <code>brand</code>, <code>unit</code></li>
                        <li><code>price</code>, <code>purchase_price</code>, <code>quantity</code></li>
                        <li><code>discount</code>, <code>discount_type</code>, <code>expire_date</code>, <code>status</code>, <code>image</code></li>
                    </ul>
                    <p class="mb-0 text-muted">
                        หากหมวดสินค้า แบรนด์ หรือหน่วยนับยังไม่มี ระบบจะสร้างให้อัตโนมัติ และถ้า SKU ซ้ำ ระบบจะอัปเดตรายการเดิมแทนสร้างสินค้าซ้ำ
                    </p>
                </div>
            </div>
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
  document.getElementById('productImportFile')?.addEventListener('change', function () {
    const fileName = this.files.length ? this.files[0].name : 'เลือกไฟล์ Excel หรือ CSV';
    this.nextElementSibling.textContent = fileName;
  });
</script>
@endpush
