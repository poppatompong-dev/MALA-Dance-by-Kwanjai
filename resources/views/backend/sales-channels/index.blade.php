@extends('backend.master')

@section('title', 'ช่องทางการขาย')

@section('content')
<div class="card">
    <div class="mt-n5 mb-3 d-flex justify-content-end">
        <a href="{{ route('backend.admin.sales-channels.create') }}" class="btn bg-gradient-primary">
            <i class="fas fa-plus-circle"></i>
            เพิ่มช่องทางการขาย
        </a>
    </div>
    <div class="card-body p-2 p-md-4 pt-0">
        <table id="datatables" class="table table-hover">
            <thead>
                <tr>
                    <th data-orderable="false">#</th>
                    <th>ช่องทาง</th>
                    <th>Slug</th>
                    <th>ค่า Commission</th>
                    <th>ลำดับ</th>
                    <th>สถานะ</th>
                    <th data-orderable="false">จัดการ</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@push('script')
<script>
$(function() {
    $('#datatables').DataTable({
        processing: true,
        serverSide: true,
        ajax: { url: "{{ route('backend.admin.sales-channels.index') }}" },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex' },
            { data: 'name_badge', name: 'name' },
            { data: 'slug', name: 'slug' },
            { data: 'commission_formatted', name: 'commission_percent' },
            { data: 'sort_order', name: 'sort_order' },
            { data: 'status_badge', name: 'status' },
            { data: 'action', name: 'action' },
        ]
    });
});
</script>
@endpush
