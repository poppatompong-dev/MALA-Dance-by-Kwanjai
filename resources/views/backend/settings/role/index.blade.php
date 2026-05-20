@extends('backend.master')

@section('title', 'บทบาท')

@section('content')
<div class="card">
    <div class="mt-n5 mb-3 d-flex justify-content-end">
        @can('role_create')
        <button class="btn bg-gradient-primary" data-toggle="modal" data-target="#roleModal">
            <i class="fas fa-plus-circle"></i>
            เพิ่มบทบาท
        </button>
        @endcan
        <!-- Modal -->
        <div class="modal fade" id="roleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                {!! Form::open(['url' => route('backend.admin.roles.create'), 'method' => 'post']) !!}
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            <i class="fas fa-plus-circle"></i>
                            เพิ่มบทบาท
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            {!! Form::label('name', 'ชื่อบทบาท') !!}
                            {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'เช่น Cashier']) !!}
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-gradient-secondary" data-dismiss="modal">ปิด</button>
                        <button class="btn bg-gradient-primary">บันทึก</button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12 table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ชื่อบทบาท</th>
                            <th class="text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $role)
                        <tr>
                            <td> {{ $role->name }} </td>
                            <td>
                                <div class="text-center">
                                    <a title="ตั้งค่าสิทธิ์"
                                        href="{{ route('backend.admin.roles.show', $role->id) }}" type="button"
                                        class="btn btn-dark btn-xs">
                                        <i class="fas fa-cog"></i>
                                    </a>
                                    @if ($role->id != 1)
                                    <button title="แก้ไขบทบาท" type="button" class="btn bg-gradient-primary btn-xs"
                                        data-toggle="modal" data-target="#editRole-{{ $role->id }}">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                    <a title="ลบบทบาท"
                                        href="{{ route('backend.admin.roles.delete', $role->id) }}"
                                        type="button" class="btn btn-danger btn-xs"
                                        onclick="return confirm('ยืนยันการลบบทบาทนี้?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                    @endif
                                </div>

                                <!-- Modal -->
                                <div class="modal fade" id="editRole-{{ $role->id }}" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        {!! Form::open(['method' => 'put', 'route' => ['backend.admin.roles.update', $role->id]]) !!}
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title fs-5" id="exampleModalLabel">
                                                    <i class="fas fa-pencil-alt"></i>
                                                    แก้ไขบทบาท
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label class="control-label">ชื่อบทบาท:</label>
                                                    {!! Form::text('name', $role->name, ['class' => 'form-control', 'placeholder' => 'เช่น Cashier']) !!}
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn bg-gradient-secondary"
                                                    data-dismiss="modal">
                                                    ปิด
                                                </button>
                                                <button type="submit" class="btn bg-gradient-primary">
                                                    บันทึกการเปลี่ยนแปลง
                                                </button>
                                            </div>
                                        </div>
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
