@extends('backend.master')

@section('title', 'แก้ไขกฎการสะสมแต้ม/ของรางวัล')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('backend.admin.reward-rules.update', $rewardRule->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>ชื่อรางวัล <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ $rewardRule->name }}" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label>ประเภท <span class="text-danger">*</span></label>
                    <select name="type" class="form-control" required>
                        <option value="earn_points" {{ $rewardRule->type == 'earn_points' ? 'selected' : '' }}>Earn Points</option>
                        <option value="redeem_points" {{ $rewardRule->type == 'redeem_points' ? 'selected' : '' }}>Redeem Points</option>
                        <option value="coupon" {{ $rewardRule->type == 'coupon' ? 'selected' : '' }}>Coupon</option>
                        <option value="tier" {{ $rewardRule->type == 'tier' ? 'selected' : '' }}>Tier Benefit</option>
                        <option value="birthday" {{ $rewardRule->type == 'birthday' ? 'selected' : '' }}>Birthday</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label>สิทธิประโยชน์ <span class="text-danger">*</span></label>
                    <select name="benefit_type" class="form-control" required>
                        <option value="percent_discount" {{ $rewardRule->benefit_type == 'percent_discount' ? 'selected' : '' }}>ส่วนลด %</option>
                        <option value="fixed_discount" {{ $rewardRule->benefit_type == 'fixed_discount' ? 'selected' : '' }}>ส่วนลด จำนวนเงิน</option>
                        <option value="bonus_points" {{ $rewardRule->benefit_type == 'bonus_points' ? 'selected' : '' }}>คะแนนโบนัส</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3 mb-3">
                    <label>มูลค่าสิทธิประโยชน์ (ตัวเลข) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="benefit_value" class="form-control" value="{{ $rewardRule->benefit_value }}" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label>แต้มที่ต้องใช้ <span class="text-muted">(ถ้ามี)</span></label>
                    <input type="number" name="required_points" class="form-control" value="{{ $rewardRule->required_points }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label>ยอดซื้อขั้นต่ำ (บาท)</label>
                    <input type="number" step="0.01" name="min_purchase" class="form-control" value="{{ $rewardRule->min_purchase }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label>รหัสคูปอง <span class="text-muted">(ถ้าเป็นคูปอง)</span></label>
                    <input type="text" name="coupon_code" class="form-control" value="{{ $rewardRule->coupon_code }}">
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label>จำกัดการใช้รวม (ครั้ง)</label>
                    <input type="number" name="usage_limit" class="form-control" value="{{ $rewardRule->usage_limit }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label>จำกัดต่อลูกค้า (ครั้ง)</label>
                    <input type="number" name="per_customer_limit" class="form-control" value="{{ $rewardRule->per_customer_limit }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label>วันที่เริ่ม</label>
                    <input type="datetime-local" name="start_date" class="form-control" value="{{ $rewardRule->start_date ? $rewardRule->start_date->format('Y-m-d\TH:i') : '' }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label>วันที่สิ้นสุด</label>
                    <input type="datetime-local" name="end_date" class="form-control" value="{{ $rewardRule->end_date ? $rewardRule->end_date->format('Y-m-d\TH:i') : '' }}">
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-4">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="statusSwitch" name="status" {{ $rewardRule->status ? 'checked' : '' }}>
                        <label class="custom-control-label" for="statusSwitch">เปิดใช้งาน (Active)</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="stackableSwitch" name="is_stackable" {{ $rewardRule->is_stackable ? 'checked' : '' }}>
                        <label class="custom-control-label" for="stackableSwitch">ใช้ร่วมกับโปรโมชั่นอื่นได้ (Stackable)</label>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> บันทึกการเปลี่ยนแปลง</button>
                <a href="{{ route('backend.admin.reward-rules.index') }}" class="btn btn-secondary">ยกเลิก</a>
            </div>
        </form>
    </div>
</div>
@endsection
