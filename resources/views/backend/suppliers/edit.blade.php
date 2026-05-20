@extends('backend.master')

@section('title', 'แก้ไขซัพพลายเออร์')

@section('content')
<div class="card">
  <div class="card-body">
    <form action="{{ route('backend.admin.suppliers.update',$supplier->id) }}" method="post" class="accountForm"
      enctype="multipart/form-data">
      @method('PUT')
      @csrf
      <div class="card-body">
        <div class="row">
          <div class="mb-3 col-md-6">
            <label for="title" class="form-label">
              ชื่อซัพพลายเออร์
              <span class="text-danger">*</span>
            </label>
            <input type="text" class="form-control" placeholder="เช่น ร้านวัตถุดิบสด" name="name"
              value="{{ $supplier->name }}" required>
          </div>
          <div class="mb-3 col-md-6">
            <label for="title" class="form-label">
              เบอร์โทร
              <span class="text-danger">*</span>
            </label>
            <input type="text" class="form-control" placeholder="เช่น 0811111111" name="phone"
              value="{{ $supplier->phone }}" required>
          </div>
          <div class="mb-3 col-md-6">
            <label for="title" class="form-label">
              ที่อยู่/หมายเหตุ
            </label>
            <input type="text" class="form-control" placeholder="เช่น ตลาดสด" name="address"
              value="{{ $supplier->address }}">
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <button type="submit" class="btn bg-gradient-primary">อัปเดต</button>
          </div>
        </div>
      </div>
      <!-- /.card-body -->
    </form>
  </div>
</div>
@endsection
@push('script')
<script>
</script>
@endpush
