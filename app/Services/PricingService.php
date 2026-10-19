<?php

namespace App\Services;

use App\Models\Coupon;

class PricingService
{
    public const SESSION_KEY = 'coupon_code';

    public const SHIPPING_FEE = 15000;

    public const FREE_SHIPPING_FROM = 150000;

    /**
     * @return array{subtotal: int, discount: int, shipping_fee: int, total: int, coupon: ?Coupon, notice: ?string}
     */
    public function quote(int $subtotal, string $fulfillment = 'delivery'): array
    {
        $resolved = $this->resolveCoupon($subtotal);
        $discount = $resolved['coupon'] ? $resolved['coupon']->discountFor($subtotal) : 0;
        $shipping = $this->shippingFee($fulfillment, $subtotal);

        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'shipping_fee' => $shipping,
            'total' => max(0, $subtotal - $discount) + $shipping,
            'coupon' => $resolved['coupon'],
            'notice' => $resolved['notice'],
        ];
    }

    public function shippingFee(string $fulfillment, int $subtotal): int
    {
        if ($fulfillment === 'takeaway') {
            return 0;
        }

        if ($subtotal >= self::FREE_SHIPPING_FROM) {
            return 0;
        }

        return self::SHIPPING_FEE;
    }

    public function rememberCoupon(string $code): void
    {
        session([self::SESSION_KEY => strtoupper(trim($code))]);
    }

    public function forgetCoupon(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    /**
     * @return array{coupon: ?Coupon, notice: ?string}
     */
    protected function resolveCoupon(int $subtotal): array
    {
        $code = session(self::SESSION_KEY);
        if (! is_string($code) || $code === '') {
            return ['coupon' => null, 'notice' => null];
        }

        $coupon = Coupon::where('code', $code)->first();
        if (! $coupon || ! $coupon->isCurrentlyValid()) {
            return ['coupon' => null, 'notice' => 'Mã giảm giá không còn hiệu lực.'];
        }

        if ($subtotal < $coupon->min_order_value) {
            return ['coupon' => null, 'notice' => 'Đơn chưa đạt giá trị tối thiểu của mã giảm giá.'];
        }

        return ['coupon' => $coupon, 'notice' => null];
    }
}
