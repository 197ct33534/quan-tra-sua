@if($order->payment_method === 'vietqr')
    @php
        $bank = config('services.vietqr.bank_id');
        $account = config('services.vietqr.account_no');
        $accountName = config('services.vietqr.account_name');
        $qrReady = $bank && $account && $accountName;
    @endphp
    <div class="bg-surface-container-lowest rounded-xl p-md shadow-soft text-center">
        <h2 class="text-headline-sm text-on-surface mb-sm">Chuyển khoản VietQR</h2>
        @if($qrReady)
            <img
                src="https://img.vietqr.io/image/{{ $bank }}-{{ $account }}-compact2.png?amount={{ (int) $order->total }}&addInfo={{ urlencode($order->order_code) }}&accountName={{ urlencode($accountName) }}"
                alt="VietQR {{ $order->order_code }}"
                class="mx-auto w-full max-w-[280px] rounded-lg bg-white"
            >
        @else
            <p class="text-body-md text-on-surface-variant">Quán chưa cấu hình tài khoản VietQR.</p>
        @endif
        <p class="text-label-md text-on-surface-variant mt-sm">Nội dung chuyển khoản mặc định là mã đơn hàng. Quán sẽ xử lý đơn ngay khi nhận được thanh toán.</p>
    </div>
@endif
