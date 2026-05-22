@extends('backend.master')

@section('title', 'กฎการสะสมแต้มและของรางวัล')

@section('content')
<div class="card">
  <div class="mt-n5 mb-3 d-flex justify-content-end">
    <a href="{{ route('backend.admin.reward-rules.create') }}" class="btn bg-gradient-primary">
      <i class="fas fa-plus-circle"></i>
      เพิ่มกฎของรางวัล
    </a>
  </div>
  <div class="card-body p-2 p-md-4 pt-0">
    <div class="row g-4">
      <div class="col-md-12">
        <div class="card-body table-responsive p-0" id="table_data">
          <table id="datatables" class="table table-hover">
            <thead>
              <tr>
                <th data-orderable="false">#</th>
                <th>ชื่อโปรโมชั่น/รางวัล</th>
                <th>ประเภท</th>
                <th>สิทธิประโยชน์</th>
                <th>ต้องการแต้ม</th>
                <th>สถานะ</th>
                <th data-orderable="false">จัดการ</th>
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
      ajax: {
        url: "{{ route('backend.admin.reward-rules.index') }}"
      },
      columns: [
        { data: 'DT_RowIndex', name: 'DT_RowIndex' },
        { data: 'name', name: 'name' },
        { data: 'type_formatted', name: 'type' },
        { data: 'benefit', name: 'benefit' },
        { data: 'required_points', name: 'required_points' },
        { data: 'status', name: 'status' },
        { data: 'action', name: 'action' },
      ]
    });
  });
</script>
@endpush
