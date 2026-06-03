@extends('layouts.admin')

@section('title', 'Thêm danh mục')
@section('heading', 'Thêm danh mục')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body" style="max-width: 640px;">
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            @include('admin.categories._form', ['submit' => 'Thêm danh mục'])
        </form>
    </div>
</div>
@endsection
