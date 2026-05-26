@csrf
<div class="row">
    <div class="col-md-6 mb-3">
        <label>ชื่อช่องทาง <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $salesChannel->name ?? '') }}" required>
    </div>
    <div class="col-md-3 mb-3">
        <label>Slug <small class="text-muted">(เว้นไว้ระบบจะสร้างให้)</small></label>
        <input type="text" name="slug" class="form-control" value="{{ old('slug', $salesChannel->slug ?? '') }}"
            {{ isset($salesChannel) && $salesChannel->slug === 'walk_in' ? 'readonly' : '' }}>
    </div>
    <div class="col-md-3 mb-3">
        <label>ค่า Commission (%) <span class="text-danger">*</span></label>
        <input type="number" step="0.01" min="0" max="100" name="commission_percent" class="form-control"
            value="{{ old('commission_percent', $salesChannel->commission_percent ?? 0) }}" required>
    </div>
</div>
<div class="row">
    <div class="col-md-4 mb-3">
        <label>Icon class <small class="text-muted">(FontAwesome เช่น fas fa-motorcycle)</small></label>
        <input type="text" name="icon" class="form-control" value="{{ old('icon', $salesChannel->icon ?? '') }}">
    </div>
    <div class="col-md-4 mb-3">
        <label>สี (Hex)</label>
        <input type="color" name="color" class="form-control" value="{{ old('color', $salesChannel->color ?? '#6c757d') }}">
    </div>
    <div class="col-md-4 mb-3">
        <label>ลำดับการแสดง</label>
        <input type="number" min="0" name="sort_order" class="form-control" value="{{ old('sort_order', $salesChannel->sort_order ?? 0) }}">
    </div>
</div>
<div class="mb-3">
    <label>รายละเอียด</label>
    <textarea name="description" class="form-control" rows="2">{{ old('description', $salesChannel->description ?? '') }}</textarea>
</div>
<div class="custom-control custom-switch mb-3">
    <input type="checkbox" class="custom-control-input" id="statusSwitch" name="status"
        {{ old('status', $salesChannel->status ?? true) ? 'checked' : '' }}>
    <label class="custom-control-label" for="statusSwitch">เปิดใช้งาน</label>
</div>
<button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> บันทึก</button>
<a href="{{ route('backend.admin.sales-channels.index') }}" class="btn btn-secondary">ยกเลิก</a>
