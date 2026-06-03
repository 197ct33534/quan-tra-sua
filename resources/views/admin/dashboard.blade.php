@extends('layouts.admin')

@section('title', 'Tổng quan')
@section('heading', 'Tổng quan')

@section('content')
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="text-muted small">Tổng đơn hàng</div>
                    <div class="fs-3 fw-bold">{{ $stats['orders_total'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="text-muted small">Chờ xác nhận</div>
                    <div class="fs-3 fw-bold text-warning">{{ $stats['orders_pending'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="text-muted small">Doanh thu (hoàn thành)</div>
                    <div class="fs-4 fw-bold text-success">@vnd($stats['revenue'])</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="text-muted small">Sản phẩm / Danh mục</div>
                    <div class="fs-3 fw-bold">{{ $stats['products'] }} / {{ $stats['categories'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white fw-semibold">Đơn hàng mới nhất</div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Mã đơn</th><th>Khách</th><th>Ngày</th>
                        <th class="text-end">Tổng</th><th class="text-center">Trạng thái</th><th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                        <tr>
                            <td class="fw-semibold">{{ $order->order_code }}</td>
                            <td>{{ $order->customer_name }}</td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-end">@vnd($order->total)</td>
                            <td class="text-center">
                                <span class="badge bg-{{ $order->status_color }}">{{ $order->status_label }}</span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-secondary">Xem</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-3">Chưa có đơn hàng.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
