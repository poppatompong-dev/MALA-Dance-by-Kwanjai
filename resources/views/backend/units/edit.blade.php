@extends('backend.master')

@section('title', 'แก้ไขหน่วยสินค้า')

@section('content')
<div class="card">
  <div class="card-body">
    <form action="{{ route('backend.admin.units.update',$unit->id) }}" method="post" class="accountForm"
      enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <div class="card-body">
        <div class="row">
          <div class="mb-3 col-md-6">
            <label for="title" class="form-label">
              ชื่อหน่วย
              <span class="text-danger">*</span>
            </label>
            <input type="text" class="form-control" placeholder="เช่น ไม้" name="title"
              value="{{ old('title',$unit->title) }}" required>
          </div>
          <div class="mb-3 col-md-6">
            <label for="short_name" class="form-label">
              ชื่อย่อ
              <span class="text-danger">*</span>
            </label>
            <input type="text" class="form-control" placeholder="เช่น ไม้" name="short_name"
              value="{{ old('short_name',$unit->short_name) }}" required>
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
<script src="{{ asset('js/image-field.js') }}"></script>
@endpush
