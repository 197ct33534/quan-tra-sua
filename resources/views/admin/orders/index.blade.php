@extends('layouts.admin')

@section('title', 'Đơn hàng')
@section('heading', 'Quản lý đơn hàng')

@section('content')
    <div id="order-desk" data-latest-id="{{ $latestId }}" data-check-url="{{ route('admin.orders.check') }}">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-2 flex-grow-1">
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
            <button type="button" id="bell-toggle" class="btn btn-outline-secondary">
                🔊 Bật chuông thông báo
            </button>
        </div>

        <div id="orders-board">
            @include('admin.orders._board', ['orders' => $orders])
        </div>
    </div>
@endsection

@push('scripts')
<script>
    (function () {
        const desk = document.getElementById('order-desk');
        const toggle = document.getElementById('bell-toggle');
        if (!desk || !toggle) return;

        let latestId = parseInt(desk.dataset.latestId || '0', 10);
        let audioCtx = null;

        function beep() {
            if (!audioCtx) return;
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.type = 'sine';
            osc.frequency.value = 880;
            gain.gain.value = 0.06;
            osc.connect(gain);
            gain.connect(audioCtx.destination);
            osc.start();
            gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.25);
            osc.stop(audioCtx.currentTime + 0.26);
        }

        toggle.addEventListener('click', function () {
            const Ctx = window.AudioContext || window.webkitAudioContext;
            if (!Ctx) return;
            audioCtx = audioCtx || new Ctx();
            audioCtx.resume();
            toggle.classList.remove('btn-outline-secondary');
            toggle.classList.add('btn-success');
            toggle.textContent = '🔔 Chuông đang bật';
            beep();
        });

        async function refreshBoard() {
            const url = new URL(window.location.href);
            url.searchParams.set('partial', '1');
            const response = await fetch(url.toString(), {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' }
            });
            if (!response.ok) return;
            document.getElementById('orders-board').innerHTML = await response.text();
        }

        async function poll() {
            const response = await fetch(desk.dataset.checkUrl + '?after_id=' + latestId, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!response.ok) return;
            const data = await response.json();
            if ((data.latest_id || 0) <= latestId) return;
            latestId = data.latest_id;
            desk.dataset.latestId = String(latestId);
            beep();
            await refreshBoard();
        }

        setInterval(poll, 20000);
    })();
</script>
@endpush
