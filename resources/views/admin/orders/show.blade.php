@extends('layouts.admin')

@section('title', 'Đơn ' . $order->order_code)
@section('heading', 'Chi tiết đơn hàng')

@section('content')
    <div class="d-flex gap-2 mb-3">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Danh sách đơn
        </a>
        <a href="{{ route('admin.orders.print', $order) }}" class="btn btn-dark btn-sm" target="_blank">
            <i class="bi bi-printer"></i> In bill K80
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <span class="fw-semibold">Đơn {{ $order->order_code }}</span>
                    <span class="badge bg-{{ $order->status_color }}">{{ $order->status_label }}</span>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr><th>Sản phẩm</th><th class="text-center">SL</th>
                                <th class="text-end">Đơn giá</th><th class="text-end">Thành tiền</th></tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <span class="fw-semibold">{{ $item->product_name }}</span>
                                        @if($item->size)<small class="d-block text-muted">Size: {{ $item->size }}</small>@endif
                                        @if(!empty($item->toppings))
                                            <small class="d-block text-muted">Topping: {{ collect($item->toppings)->pluck('name')->join(', ') }}</small>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">@vnd($item->unit_price)</td>
                                    <td class="text-end">@vnd($item->line_total)</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr><td colspan="3" class="text-end">Tạm tính</td><td class="text-end">@vnd($order->subtotal)</td></tr>
                            @if($order->discount > 0)
                                <tr><td colspan="3" class="text-end">Giảm giá {{ $order->coupon_code }}</td><td class="text-end">-@vnd($order->discount)</td></tr>
                            @endif
                            <tr><td colspan="3" class="text-end">Phí giao hàng</td><td class="text-end">@vnd($order->shipping_fee)</td></tr>
                            <tr class="fw-bold"><td colspan="3" class="text-end">Tổng cộng</td>
                                <td class="text-end text-brand">@vnd($order->total)</td></tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-semibold">Khách hàng</div>
                <div class="card-body">
                    <p class="mb-1"><i class="bi bi-person"></i> {{ $order->customer_name }}</p>
                    <p class="mb-1"><i class="bi bi-telephone"></i> {{ $order->phone }}</p>
                    <p class="mb-1"><i class="bi bi-geo-alt"></i> {{ $order->fulfillment === 'takeaway' ? 'Nhận tại quán' : ($order->address ?: '—') }}</p>
                    @if($order->note)<p class="mb-1"><i class="bi bi-chat-left-text"></i> {{ $order->note }}</p>@endif
                    <p class="mb-0 text-muted small">Tài khoản: {{ $order->user->email ?? '—' }}</p>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-semibold">Cập nhật trạng thái</div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                        @csrf @method('PUT')
                        <select name="status" class="form-select mb-3">
                            @foreach($order->nextStatuses() as $key => $label)
                                <option value="{{ $key }}" {{ $order->status === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-brand w-100"><i class="bi bi-check2"></i> Lưu trạng thái</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
