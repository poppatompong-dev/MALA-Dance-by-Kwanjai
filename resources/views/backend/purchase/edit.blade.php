@extends('backend.master')

@section('title', 'แก้ไขข้อมูลซื้อเข้า')

@section('content')
<div class="card">
  <div class="card-body">
    <form action="{{ route('backend.admin.customers.update', $customer->id) }}" method="post" class="accountForm"
      enctype="multipart/form-data">
      @method('PUT')
      @csrf
      <div class="card-body row">
        <div class="mb-3 col-md-6">
          <label for="name" class="form-label">
            ชื่อ
            <span class="text-danger">*</span>
          </label>
          <input type="text" class="form-control" id="name" name="name" placeholder="กรอกชื่อ"
            value="{{ $customer->name }}" required>
        </div>
        <div class="mb-3 col-md-6">
          <label for="phone" class="form-label">
            เบอร์โทร
            <span class="text-danger">*</span>
          </label>
          <input type="text" class="form-control" id="phone" name="phone" placeholder="กรอกเบอร์โทร"
            value="{{ $customer->phone }}" required>
        </div>
        <div class="mb-3 col-md-6">
          <label for="address" class="form-label">ที่อยู่</label>
          <input type="text" class="form-control" id="address" name="address" placeholder="กรอกที่อยู่"
            value="{{ $customer->address }}">
        </div>
      </div>
      <button type="submit" class="btn btn-block bg-gradient-primary">อัปเดต</button>
    </form>
  </div>
</div>
@endsection
