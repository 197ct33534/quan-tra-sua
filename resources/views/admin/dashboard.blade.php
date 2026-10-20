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

    <div class="row g-3 mb-4">
        {{-- Doanh thu 7 ngày --}}
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white fw-semibold d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-bar-chart-line me-2 text-success"></i>Doanh thu 7 ngày gần nhất</span>
                </div>
                <div class="card-body">
                    @php $maxRevenue = max($days->values()->toArray() ?: [1]); @endphp
                    <div class="d-flex align-items-end gap-2" style="height:140px">
                        @foreach($days as $date => $rev)
                            @php $pct = $maxRevenue > 0 ? round(($rev / $maxRevenue) * 100) : 0; @endphp
                            <div class="flex-fill d-flex flex-column align-items-center justify-content-end gap-1">
                                <small class="text-muted" style="font-size:10px">{{ number_format($rev/1000) }}k</small>
                                <div class="rounded-top {{ $rev > 0 ? 'bg-success' : 'bg-light border' }}"
                                     style="width:100%; height:{{ max($pct, 4) }}%; min-height:4px"></div>
                                <small class="text-muted" style="font-size:10px">{{ \Carbon\Carbon::parse($date)->format('d/m') }}</small>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Top sản phẩm --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-trophy me-2 text-warning"></i>Top 5 bán chạy
                </div>
                <ul class="list-group list-group-flush">
                    @forelse($topProducts as $i => $tp)
                        <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                            <span>
                                <span class="badge rounded-pill me-2
                                    {{ $i === 0 ? 'bg-warning text-dark' : ($i === 1 ? 'bg-secondary' : 'bg-light text-muted border') }}">
                                    {{ $i + 1 }}
                                </span>
                                <small>{{ Str::limit($tp->product_name, 22) }}</small>
                            </span>
                            <span class="badge bg-primary rounded-pill">{{ $tp->total_qty }} ly</span>
                        </li>
                    @empty
                        <li class="list-group-item text-center text-muted py-3">Chưa có dữ liệu.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    {{-- Đơn hàng mới nhất --}}
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
