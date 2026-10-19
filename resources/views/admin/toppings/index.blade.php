@extends('layouts.admin')

@section('title', 'Topping')
@section('heading', 'Topping')

@section('content')
    @if(auth()->user()->isAdmin())
        <div class="mb-3 text-end">
            <a href="{{ route('admin.toppings.create') }}" class="btn btn-brand">
                <i class="bi bi-plus-lg"></i> Thêm topping
            </a>
        </div>
    @endif
    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr><th>#</th><th>Tên</th><th class="text-end">Giá</th><th class="text-center">Kích hoạt</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse($toppings as $topping)
                        <tr>
                            <td>{{ $topping->id }}</td>
                            <td class="fw-semibold">{{ $topping->name }}</td>
                            <td class="text-end">@vnd($topping->price)</td>
                            <td class="text-center">
                                <div class="form-check form-switch d-inline-flex align-items-center gap-2">
                                    <input class="form-check-input availability-toggle" type="checkbox" role="switch"
                                           data-url="{{ route('admin.toppings.availability', $topping) }}"
                                           data-field="active"
                                           data-on="Bật" data-off="Tắt"
                                           @checked($topping->is_active)>
                                    <span class="availability-label small">{{ $topping->is_active ? 'Bật' : 'Tắt' }}</span>
                                </div>
                            </td>
                            <td class="text-end">
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('admin.toppings.edit', $topping) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('admin.toppings.destroy', $topping) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Xóa topping này?');">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-3">Chưa có topping.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
