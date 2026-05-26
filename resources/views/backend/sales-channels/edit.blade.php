@extends('backend.master')
@section('title', 'แก้ไขช่องทางการขาย')
@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('backend.admin.sales-channels.update', $salesChannel->id) }}" method="POST">
            @method('PUT')
            @include('backend.sales-channels._form')
        </form>
    </div>
</div>
@endsection
