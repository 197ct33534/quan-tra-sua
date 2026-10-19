@extends('layouts.admin')

@section('title', 'Sản phẩm')
@section('heading', 'Sản phẩm')

@section('content')
    <div class="d-flex justify-content-between mb-3 flex-wrap gap-2">
        <form action="{{ route('admin.products.index') }}" method="GET" class="d-flex" style="max-width: 320px;">
            <input type="search" name="q" class="form-control" placeholder="Tìm sản phẩm..." value="{{ request('q') }}">
            <button class="btn btn-outline-secondary ms-1"><i class="bi bi-search"></i></button>
        </form>
        @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.products.create') }}" class="btn btn-brand">
                <i class="bi bi-plus-lg"></i> Thêm sản phẩm
            </a>
        @endif
    </div>
    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Ảnh</th><th>Tên</th><th>Danh mục</th>
                        <th class="text-end">Giá</th><th class="text-center">Tùy chọn</th>
                        <th class="text-center">Còn hàng</th><th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td><img src="{{ $product->image_url }}" width="48" height="48"
                                     class="rounded" style="object-fit:cover;" alt=""></td>
                            <td class="fw-semibold">{{ $product->name }}</td>
                            <td>{{ $product->category->name ?? '—' }}</td>
                            <td class="text-end">@vnd($product->price)</td>
                            <td class="text-center">
                                @if($product->has_options)<span class="badge bg-info">Size/Topping</span>@else—@endif
                            </td>
                            <td class="text-center">
                                <div class="form-check form-switch d-inline-flex align-items-center gap-2">
                                    <input class="form-check-input availability-toggle" type="checkbox" role="switch"
                                           data-url="{{ route('admin.products.availability', $product) }}"
                                           data-field="available"
                                           data-on="Còn" data-off="Hết"
                                           @checked($product->is_available)>
                                    <span class="availability-label small">{{ $product->is_available ? 'Còn' : 'Hết' }}</span>
                                </div>
                            </td>
                            <td class="text-end">
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Xóa sản phẩm này?');">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-3">Chưa có sản phẩm.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $products->links('pagination::bootstrap-5') }}</div>
@endsection
