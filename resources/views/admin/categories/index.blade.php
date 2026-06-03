@extends('layouts.admin')

@section('title', 'Danh mục')
@section('heading', 'Danh mục sản phẩm')

@section('content')
    <div class="mb-3 text-end">
        <a href="{{ route('admin.categories.create') }}" class="btn btn-brand">
            <i class="bi bi-plus-lg"></i> Thêm danh mục
        </a>
    </div>
    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th><th>Tên</th><th>Mô tả</th>
                        <th class="text-center">Số SP</th><th class="text-center">Hiển thị</th>
                        <th class="text-center">Thứ tự</th><th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td class="fw-semibold">{{ $category->name }}</td>
                            <td class="text-muted">{{ Str::limit($category->description, 50) }}</td>
                            <td class="text-center">{{ $category->products_count }}</td>
                            <td class="text-center">
                                @if($category->is_active)
                                    <span class="badge bg-success">Có</span>
                                @else
                                    <span class="badge bg-secondary">Ẩn</span>
                                @endif
                            </td>
                            <td class="text-center">{{ $category->sort_order }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i></a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Xóa danh mục này? Tất cả sản phẩm thuộc danh mục cũng bị xóa.');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-3">Chưa có danh mục.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
