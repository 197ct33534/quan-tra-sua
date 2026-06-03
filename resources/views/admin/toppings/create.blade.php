@extends('layouts.admin')

@section('title', 'Thêm topping')
@section('heading', 'Thêm topping')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body" style="max-width: 560px;">
        <form action="{{ route('admin.toppings.store') }}" method="POST">
            @csrf
            @include('admin.toppings._form', ['submit' => 'Thêm topping'])
        </form>
    </div>
</div>
@endsection
