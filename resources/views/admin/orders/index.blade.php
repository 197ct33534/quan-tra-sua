@extends('layouts.admin')

@section('title', 'Đơn hàng')
@section('heading', 'Quản lý đơn hàng')

@section('content')
    <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-2 mb-3">
        <div class="col-md-4">
            <input type="search" name="q" class="form-control" placeholder="Tìm theo mã đơn..." value="{{ request('q') }}">
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">— Tất cả trạng thái —</option>
                @foreach($statuses as $key => $label)
                    <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-brand w-100"><i class="bi bi-funnel"></i> Lọc</button>
        </div>
    </form>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Mã đơn</th><th>Khách</th><th>SĐT</th><th>Ngày</th>
                        <th class="text-end">Tổng</th><th class="text-center">Trạng thái</th><th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td class="fw-semibold">{{ $order->order_code }}</td>
                            <td>{{ $order->customer_name }}</td>
                            <td>{{ $order->phone }}</td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-end">@vnd($order->total)</td>
                            <td class="text-center">
                                <span class="badge bg-{{ $order->status_color }}">{{ $order->status_label }}</span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-eye"></i> Xem</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-3">Không có đơn hàng.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $orders->links('pagination::bootstrap-5') }}</div>
@endsection
