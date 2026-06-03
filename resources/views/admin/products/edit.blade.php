@extends('layouts.admin')

@section('title', 'Sửa sản phẩm')
@section('heading', 'Sửa sản phẩm')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            @include('admin.products._form', ['submit' => 'Lưu thay đổi'])
        </form>
    </div>
</div>
@endsection
