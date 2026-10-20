<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Bill {{ $order->order_code }}</title>
    <style>
        * { box-sizing: border-box; }
        body {
            width: 80mm;
            margin: 0 auto;
            padding: 4mm;
            color: #000;
            background: #fff;
            font-family: "Courier New", ui-monospace, monospace;
            font-size: 12px;
            line-height: 1.35;
        }
        h1 { font-size: 16px; text-align: center; margin: 0 0 2mm; }
        .center { text-align: center; }
        .bold { font-weight: 700; }
        .line { border-top: 1px dashed #000; margin: 2mm 0; }
        .row { display: flex; justify-content: space-between; gap: 2mm; }
        .item { margin-bottom: 2mm; }
        .actions { margin-top: 4mm; text-align: center; }
        button { font: inherit; padding: 2mm 4mm; }
        @media print {
            @page { size: 80mm auto; margin: 2mm; }
            body { width: 80mm; padding: 0; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
    <h1>{{ config('app.name') }}</h1>
    <p class="center bold">{{ $order->order_code }}</p>
    <p class="center">{{ $order->created_at->format('d/m/Y H:i') }}</p>
    <div class="line"></div>
    <p>{{ $order->customer_name }} · {{ $order->phone }}</p>
    @if($order->fulfillment === 'takeaway')
        <p class="bold">Nhận tại quán</p>
    @else
        <p class="bold">Giao tận nơi</p>
        <p>{{ $order->address }}</p>
    @endif
    <div class="line"></div>

    @foreach($order->items as $item)
        <div class="item">
            <div class="bold">{{ $item->product_name }}@if($item->size) — Size {{ $item->size }}@endif</div>
            @if($item->sugar !== null || $item->ice !== null)
                <div>{{ \App\Models\OrderItem::sugarLabel($item->sugar) ?? '—' }} - {{ \App\Models\OrderItem::iceLabel($item->ice) ?? '—' }}</div>
            @endif
            @if($item->spice)
                <div>{{ \App\Models\OrderItem::spiceLabel($item->spice) }}</div>
            @endif
            @foreach($item->toppings ?? [] as $topping)
                <div>+ {{ $topping['name'] ?? '' }}</div>
            @endforeach
            @if($item->note)
                <div>Ghi chú: {{ $item->note }}</div>
            @endif
            <div class="row">
                <span>x{{ $item->quantity }}</span>
                <span>@vnd($item->line_total)</span>
            </div>
        </div>
    @endforeach

    @if($order->note)
        <div class="line"></div>
        <p>Ghi chú: {{ $order->note }}</p>
    @endif

    <div class="line"></div>
    <div class="row"><span>Tạm tính</span><span>@vnd($order->subtotal)</span></div>
    <div class="row"><span>Giảm giá</span><span>-@vnd($order->discount)</span></div>
    <div class="row"><span>Phí ship</span><span>@vnd($order->shipping_fee)</span></div>
    <div class="row bold"><span>Tổng thanh toán</span><span>@vnd($order->total)</span></div>
    <p class="center">{{ $order->payment_method === 'vietqr' ? 'Chuyển khoản VietQR' : 'Tiền mặt' }}</p>

    <div class="actions no-print">
        <button type="button" onclick="window.print()">In lại</button>
    </div>
    <script>
        window.addEventListener('load', function () { window.print(); });
    </script>
</body>
</html>
