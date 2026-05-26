@extends('backend.master')

@section('title', 'ยอดขายแยกตามแพลตฟอร์ม')

@section('content')
<div class="card">
    <div class="card-body">
        <form method="GET" action="{{ route('backend.admin.platform.sales.report') }}" class="form-inline mb-3">
            <div class="form-group mr-2">
                <label class="mr-2">ตั้งแต่</label>
                <input type="date" name="start_date" class="form-control" value="{{ $start_date }}">
            </div>
            <div class="form-group mr-2">
                <label class="mr-2">ถึง</label>
                <input type="date" name="end_date" class="form-control" value="{{ $end_date }}">
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> ดูรายงาน</button>
        </form>

        <div class="row mb-3">
            <div class="col-md-3">
                <div class="info-box bg-info">
                    <div class="info-box-content">
                        <span class="info-box-text">จำนวนออเดอร์</span>
                        <span class="info-box-number">{{ number_format($grandTotals['order_count']) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box bg-primary">
                    <div class="info-box-content">
                        <span class="info-box-text">ยอดขายรวมก่อนหัก commission</span>
                        <span class="info-box-number">{{ currency()->symbol ?? '' }} {{ number_format($grandTotals['net_sales'], 2) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box bg-warning">
                    <div class="info-box-content">
                        <span class="info-box-text">ค่า Commission รวม</span>
                        <span class="info-box-number">{{ currency()->symbol ?? '' }} {{ number_format($grandTotals['total_platform_fee'], 2) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box bg-success">
                    <div class="info-box-content">
                        <span class="info-box-text">ยอดสุทธิหลังหัก commission</span>
                        <span class="info-box-number">{{ currency()->symbol ?? '' }} {{ number_format($grandTotals['net_after_fee'], 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>ช่องทาง</th>
                        <th class="text-right">% Commission</th>
                        <th class="text-right">จำนวนออเดอร์</th>
                        <th class="text-right">ยอดก่อนส่วนลด</th>
                        <th class="text-right">ส่วนลด</th>
                        <th class="text-right">ยอดสุทธิ</th>
                        <th class="text-right">ค่า Commission</th>
                        <th class="text-right">รายได้จริง</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $row)
                        <tr>
                            <td>
                                <span class="badge" style="background-color:{{ $row->channel_color }};color:#fff;">
                                    <i class="{{ $row->channel_icon }}"></i> {{ $row->channel_name }}
                                </span>
                            </td>
                            <td class="text-right">{{ number_format((float)$row->commission_percent, 2) }} %</td>
                            <td class="text-right">{{ number_format($row->order_count) }}</td>
                            <td class="text-right">{{ number_format((float)$row->gross_sales, 2) }}</td>
                            <td class="text-right">{{ number_format((float)$row->total_discount, 2) }}</td>
                            <td class="text-right">{{ number_format((float)$row->net_sales, 2) }}</td>
                            <td class="text-right text-warning">{{ number_format((float)$row->total_platform_fee, 2) }}</td>
                            <td class="text-right text-success">
                                <strong>{{ number_format((float)$row->net_sales - (float)$row->total_platform_fee, 2) }}</strong>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">ไม่พบข้อมูลในช่วงเวลานี้</td>
                        </tr>
                    @endforelse
                </tbody>
                @if($rows->count() > 0)
                <tfoot>
                    <tr class="font-weight-bold bg-light">
                        <td colspan="2">รวมทั้งหมด</td>
                        <td class="text-right">{{ number_format($grandTotals['order_count']) }}</td>
                        <td class="text-right">{{ number_format($grandTotals['gross_sales'], 2) }}</td>
                        <td class="text-right">{{ number_format($grandTotals['total_discount'], 2) }}</td>
                        <td class="text-right">{{ number_format($grandTotals['net_sales'], 2) }}</td>
                        <td class="text-right text-warning">{{ number_format($grandTotals['total_platform_fee'], 2) }}</td>
                        <td class="text-right text-success">{{ number_format($grandTotals['net_after_fee'], 2) }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection
