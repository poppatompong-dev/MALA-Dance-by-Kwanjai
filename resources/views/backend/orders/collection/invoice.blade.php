@extends('backend.master')
@section('title', 'ใบรับชำระ_'.$transaction->id)
@section('content')
<div class="card">
  <div class="card-body">
    <!-- Main content -->
    <section class="invoice">
      <!-- title row -->
      <div class="row mb-4">
        <div class="col-4">
          <h2 class="page-header">
            <img src="{{ assetImage(readconfig('site_logo')) }}" height="40" width="40" alt="โลโก้ร้าน"
              class="brand-image img-circle elevation-3" style="opacity: .8"> {{ readConfig('site_name') }}
          </h2>
        </div>
        <div class="col-4">
          <h4 class="page-header">ใบรับชำระ</h4>
        </div>
        <div class="col-4">
          <small class="float-right text-small">วันที่: {{date('d/m/Y')}}</small>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
      <div class="row invoice-info mb-2">
        <!-- /.col -->
        <div class="col-sm-5 invoice-col">
          @if(readConfig('is_show_customer_invoice'))
          ลูกค้า
          <address>
            <strong>ชื่อ: {{$order->customer->name??"-"}}</strong><br>
            ที่อยู่: {{$order->customer->address??"-"}}<br>
            เบอร์โทร: {{$order->customer->phone??"-"}}<br>
          </address>
          @endif
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          ร้านค้า
          <address>
            @if(readConfig('is_show_site_invoice'))<strong>ชื่อร้าน: {{ readConfig('site_name') }}</strong><br> @endif
            @if(readConfig('is_show_address_invoice'))ที่อยู่: {{ readConfig('contact_address') }}<br>@endif
            @if(readConfig('is_show_phone_invoice'))เบอร์โทร: {{ readConfig('contact_phone') }}<br>@endif
            @if(readConfig('is_show_email_invoice'))อีเมล: {{ readConfig('contact_email') }}<br>@endif
          </address>
        </div>
        <div class="col-sm-3 invoice-col">
          ข้อมูล <br>
          เลขที่ใบรับชำระ #{{$transaction->id}}<br>
          เลขที่ขาย #{{$order->id}}<br>
          วันที่ขาย: {{date('d/m/Y', strtotime($order->created_at))}}<br>
          วันที่รับชำระ: {{date('d/m/Y', strtotime($transaction->created_at))}}<br>
          <!-- <br>
          <b>Payment Due:</b> 2/22/2014<br>
          <b>Account:</b> 968-34567 -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- Table row -->
      <div class="row">
        <div class="col-12 table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>ลำดับ</th>
                <th>สินค้า</th>
                <th>จำนวน</th>
                <th>ราคา {{currency()->symbol??''}}</th>
                <th>รวม {{currency()->symbol??''}}</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($order->products as $item )
              <tr>
                <td>{{$loop->index + 1}}</td>
                <td>{{$item->product->name}}</td>
                <td>{{$item->quantity}} {{optional($item->product->unit)->short_name}}</td>
                <td>
                  {{ $item->discounted_price }}
                  @if ($item->price>$item->discounted_price)
                  <br><del>{{ $item->price }}</del>
                  @endif
                </td>
                <td>{{number_format($item->total,2,'.',',')}}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <div class="row">
        <!-- accepted payments column -->
        <div class="col-6">
          <!-- <p class="lead">Payment:Cash Paid</p> -->
          <!-- <small class="lead text-small text-bold">Payment:Cash Paid</small> -->
          <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
            @if(readConfig('is_show_note_invoice')){{ readConfig('note_to_customer_invoice') }}@endif
          </p>
        </div>
        <!-- /.col -->
        <div class="col-6">
          <!-- <p class="lead">Amount Due 2/22/2014</p> -->

          <div class="table-responsive">
            <table class="table">
              <tr>
                <th style="width:50%">ยอดก่อนส่วนลด:</th>
                <td class="text-right">{{currency()->symbol.' '.number_format($order->sub_total,2,'.',',')}}</td>
              </tr>
              <tr>
                <th>ส่วนลด:</th>
                <td class="text-right">{{currency()->symbol.' '.number_format($order->discount,2,'.',',')}}</td>
              </tr>
              <tr>
                <th>ยอดสุทธิ:</th>
                <td class="text-right">{{currency()->symbol.' '.number_format($order->total,2,'.',',')}}</td>
              </tr>
              <tr>
                <th>ชำระแล้วก่อนหน้า:</th>
                <td class="text-right">{{currency()->symbol.' '.number_format($order->paid - $collection_amount,2,'.',',')}}</td>
              </tr>
              <tr>
                <th>ยอดรับชำระ:</th>
                <td class="text-right">{{currency()->symbol.' '.number_format($collection_amount,2,'.',',')}}</td>
              </tr>
              <tr>
                <th>ค้างชำระ:</th>
                <td class="text-right">{{currency()->symbol.' '.number_format($order->due,2,'.',',')}}</td>
              </tr>
            </table>
          </div>
        </div>
        <!-- /.col -->
      </div>
      <div class="row no-print">
        <div class="col-12">
          <button type="button" onclick="window.print()" class="btn btn-success float-right"><i class="fas fa-print"></i> พิมพ์</a>
          </button>
        </div>
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
</div>
@endsection

@push('style')
<style>
  .invoice {
    border: none !important;
  }
</style>
@endpush
@push('script')
<script>
  window.addEventListener("load", window.print());
</script>
@endpush
