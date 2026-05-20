@extends('backend.master')

@section('title', 'แก้ไขลูกค้า')

@section('content')
<div class="card">
  <div class="card-body">
    <form action="{{ route('backend.admin.customers.update',$customer->id) }}" method="post" class="accountForm"
      enctype="multipart/form-data">
      @method('PUT')
      @csrf
      <div class="card-body">
        <div class="row">
          <div class="mb-3 col-md-6">
            <label for="title" class="form-label">
              ชื่อลูกค้า
              <span class="text-danger">*</span>
            </label>
            <input type="text" class="form-control" placeholder="เช่น ลูกค้าประจำ" name="name"
              value="{{ $customer->name }}" required>
          </div>
          <div class="mb-3 col-md-6">
            <label for="title" class="form-label">
              เบอร์โทร
              <span class="text-danger">*</span>
            </label>
            <input type="text" class="form-control" placeholder="เช่น 0801111111" name="phone"
              value="{{ $customer->phone }}" required>
          </div>
          <div class="mb-3 col-md-6">
            <label for="title" class="form-label">
              ที่อยู่/หมายเหตุ
            </label>
            <input type="text" class="form-control" placeholder="เช่น พื้นที่ใกล้ร้าน" name="address"
              value="{{ $customer->address }}">
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <button type="submit" class="btn bg-gradient-primary">อัปเดต</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
@push('script')
<script>
</script>
@endpush
