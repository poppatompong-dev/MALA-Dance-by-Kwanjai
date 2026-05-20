@extends('backend.master')

@section('title', 'ผู้จำหน่าย')

@section('content')
<div class="card">

  @can('supplier_create')
  <div class="mt-n5 mb-3 d-flex justify-content-end">
    <a href="{{ route('backend.admin.suppliers.create') }}" class="btn bg-gradient-primary">
      <i class="fas fa-plus-circle"></i>
      เพิ่มผู้จำหน่าย
    </a>
  </div>
  @endcan
  <div class="card-body p-2 p-md-4 pt-0">
    <div class="row g-4">
      <div class="col-md-12">
        <div class="card-body table-responsive p-0" id="table_data">
          <table id="datatables" class="table table-hover">
            <thead>
              <tr>
                <th data-orderable="false">#</th>
                <th>ชื่อผู้จำหน่าย</th>
                <th>โทรศัพท์</th>
                <th>ที่อยู่/แหล่งวัตถุดิบ</th>
                <th>วันที่สร้าง</th>
                <th data-orderable="false">
                  จัดการ
                </th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection


@push('script')
<script type="text/javascript">
  $(function() {
    let table = $('#datatables').DataTable({
      processing: true,
      serverSide: true,
      ordering: true,
      order: [
        [1, 'asc']
      ],
      ajax: {
        url: "{{ route('backend.admin.suppliers.index') }}"
      },

      columns: [{
          data: 'DT_RowIndex',
          name: 'DT_RowIndex'
        },
        {
          data: 'name',
          name: 'name'
        },
        {
          data: 'phone',
          name: 'phone'
        },
        {
          data: 'address',
          name: 'address'
        },
        {
          data: 'created_at',
          name: 'created_at'
        },
        {
          data: 'action',
          name: 'action'
        },
      ]
    });
  });
</script>
@endpush
