<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramNotifier
{
    public function notifyNewOrder(Order $order): void
    {
        $token = config('services.telegram.bot_token');
        $chatId = config('services.telegram.chat_id');

        if (! $token || ! $chatId) {
            return;
        }

        $order->loadMissing('items');

        $lines = $order->items->map(function ($item) {
            $extra = $item->note ? ' — '.$item->note : '';

            return '• '.$item->product_name.' x'.$item->quantity.$extra;
        })->implode("\n");

        $where = $order->fulfillment === 'takeaway'
            ? 'Nhận tại quán'
            : (string) $order->address;

        $text = implode("\n", array_filter([
            'Đơn mới '.$order->order_code,
            $order->customer_name.' · '.$order->phone,
            $where,
            $lines,
            'Tổng '.number_format((int) $order->total, 0, ',', '.').'đ',
            $order->payment_method === 'vietqr' ? 'Thanh toán: VietQR' : 'Thanh toán: COD',
        ]));

        try {
            $response = Http::timeout(5)->post(
                'https://api.telegram.org/bot'.$token.'/sendMessage',
                ['chat_id' => $chatId, 'text' => $text]
            );

            if (! $response->successful()) {
                Log::warning('Telegram từ chối thông báo đơn.', [
                    'order' => $order->order_code,
                    'status' => $response->status(),
                ]);
            }
        } catch (\Throwable $e) {
            Log::warning('Không gửi được Telegram cho đơn mới.', [
                'order' => $order->order_code,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
