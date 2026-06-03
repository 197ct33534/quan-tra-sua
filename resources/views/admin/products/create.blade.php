@extends('layouts.admin')

@section('title', 'Thêm sản phẩm')
@section('heading', 'Thêm sản phẩm')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.products._form', ['submit' => 'Thêm sản phẩm'])
        </form>
    </div>
</div>
@endsection
