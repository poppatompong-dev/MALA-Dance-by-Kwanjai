@extends('backend.master')
@section('title', 'เพิ่มช่องทางการขาย')
@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('backend.admin.sales-channels.store') }}" method="POST">
            @include('backend.sales-channels._form')
        </form>
    </div>
</div>
@endsection
