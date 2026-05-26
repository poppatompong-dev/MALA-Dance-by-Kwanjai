@extends('backend.master')

@section('title', 'รายการขาย')

@section('content')
<div class="card">
  <div class="card-body p-2 p-md-4 pt-0">
    <div class="row g-4">
      <div class="col-md-12">
        <div class="card-body table-responsive p-0" id="table_data">
          <table id="datatables" class="table table-hover">
            <thead>
              <tr>
                <th data-orderable="false">#</th>
                <th>เลขที่ขาย</th>
                <th>ช่องทาง</th>
                <th>ลูกค้า</th>
                <th>จำนวนรายการ</th>
                <th>ยอดก่อนส่วนลด {{currency()->symbol??''}}</th>
                <th>ส่วนลด {{currency()->symbol??''}}</th>
                <th>ยอดสุทธิ {{currency()->symbol??''}}</th>
                <th>รับเงิน {{currency()->symbol??''}}</th>
                <th>ค้างชำระ {{currency()->symbol??''}}</th>
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
      order: [
        [1, 'desc']
      ],
      ajax: {
        url: "{{ route('backend.admin.orders.index') }}"
      },

      columns: [{
          data: 'DT_RowIndex',
          name: 'DT_RowIndex'
        },
        {
          data: 'saleId',
          name: 'saleId'
        },
        {
          data: 'channel',
          name: 'channel',
          orderable: false
        },
        {
          data: 'customer',
          name: 'customer'
        },
        {
          data: 'item',
          name: 'item'
        },
        {
          data: 'sub_total',
          name: 'sub_total'
        },
        {
          data: 'discount',
          name: 'discount'
        },
        {
          data: 'total',
          name: 'total'
        }, 
         {
          data: 'paid',
          name: 'paid'
        },
         {
          data: 'due',
          name: 'due'
        },
        {
          data: 'status',
          name: 'status'
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
