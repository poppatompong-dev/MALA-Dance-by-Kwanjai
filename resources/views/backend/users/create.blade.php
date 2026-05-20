@extends('backend.master')

@section('title', 'เพิ่มผู้ใช้งาน')

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('backend.admin.user.create') }}" method="post" class="accountForm"
                enctype="multipart/form-data">
                @csrf
                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="fullName" class="form-label">ชื่อ-นามสกุล</label>
                            <input type="text" class="form-control" id="fullName" placeholder="เช่น พนักงานขาย"
                                name="name" value="{{ old('name') }}" required>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="email" class="form-label">อีเมลเข้าสู่ระบบ</label>
                            <input type="email" class="form-control" id="email" placeholder="เช่น cashier@example.com" name="email"
                                value="{{ old('email') }}" required>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="confirmPassword" class="form-label">บทบาทและสิทธิ์</label>
                            <select class="custom-select" name="role" required>
                                <option value="">-- เลือกบทบาท --</option>
                                @foreach ($roles as $role)
                                    <option {{ old('role') == $role->id ? 'selected' : '' }} value="{{ $role->id }}">
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="password" class="form-label">รหัสผ่านเข้าสู่ระบบ</label>
                            <input type="password" class="form-control" id="password" placeholder="กรอกรหัสผ่าน"
                                name="password" value="{{ old('password') }}" required>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="thumbnail">รูปโปรไฟล์</label>
                            <input type="file" class="form-control" name="profile_image"
                                onchange="previewThumbnail(this)">
                            <img class="img-fluid thumbnail-preview" src="{{ nullImg() }}" alt="ตัวอย่างรูปโปรไฟล์">
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-block bg-gradient-primary">บันทึก</button>
            </form>
        </div>
    </div>
@endsection
