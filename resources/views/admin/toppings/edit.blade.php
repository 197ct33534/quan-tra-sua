@extends('layouts.admin')

@section('title', 'Sửa topping')
@section('heading', 'Sửa topping')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body" style="max-width: 560px;">
        <form action="{{ route('admin.toppings.update', $topping) }}" method="POST">
            @csrf @method('PUT')
            @include('admin.toppings._form', ['submit' => 'Lưu thay đổi'])
        </form>
    </div>
</div>
@endsection
