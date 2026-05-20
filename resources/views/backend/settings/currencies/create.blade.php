@extends('backend.master')

@section('title', 'เพิ่มสกุลเงิน')

@section('content')
<div class="card">
  <div class="card-body">
    <form action="{{ route('backend.admin.currencies.store') }}" method="post" class="accountForm"
      enctype="multipart/form-data">
      @csrf
      <div class="card-body">
        <div class="row">
          <div class="mb-3 col-md-6">
            <label for="name" class="form-label">
              ชื่อ
              <span class="text-danger">*</span>
            </label>
            <input type="text" class="form-control" placeholder="เช่น Thai Baht" name="name"
              value="{{ old('name') }}" required>
          </div>
          <div class="mb-3 col-md-6">
            <label for="code" class="form-label">
              รหัส
              <span class="text-danger">*</span>
            </label>
            <input type="text" class="form-control" placeholder="เช่น THB" name="code"
              value="{{ old('code') }}" required>
          </div>
          <div class="mb-3 col-md-6">
            <label for="symbol" class="form-label">
              สัญลักษณ์
              <span class="text-danger">*</span>
            </label>
            <input type="text" class="form-control" placeholder="เช่น ฿" name="symbol"
              value="{{ old('symbol') }}" required>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <button type="submit" class="btn bg-gradient-primary">บันทึก</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@push('style')


@endpush
@push('script')
<script src="{{ asset('js/image-field.js') }}"></script>
@endpush
