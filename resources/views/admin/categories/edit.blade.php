@extends('layouts.admin')

@section('title', 'Sửa danh mục')
@section('heading', 'Sửa danh mục')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body" style="max-width: 640px;">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST">
            @csrf @method('PUT')
            @include('admin.categories._form', ['submit' => 'Lưu thay đổi'])
        </form>
    </div>
</div>
@endsection
