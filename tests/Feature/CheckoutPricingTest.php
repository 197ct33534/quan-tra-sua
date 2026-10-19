<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutPricingTest extends TestCase
{
    use RefreshDatabase;

    private function product(int $price = 25000, bool $options = true): Product
    {
        $category = Category::create(['name' => 'Trà sữa', 'slug' => 'tra-sua-' . uniqid()]);

        return Product::create([
            'category_id' => $category->id,
            'name' => 'Trà sữa truyền thống',
            'slug' => 'sp-' . uniqid(),
            'price' => $price,
            'has_options' => $options,
            'is_available' => true,
        ]);
    }

    private function addDrink(Product $product, int $qty = 1, string $size = 'S'): void
    {
        $this->post(route('cart.add'), [
            'product_id' => $product->id,
            'quantity' => $qty,
            'size' => $size,
        ]);
    }

    public function test_ma_giam_gia_chi_tru_tam_tinh_khong_tru_phi_ship(): void
    {
        $product = $this->product(25000);
        $this->addDrink($product, 1, 'M');

        Coupon::create([
            'code' => 'GIAM10',
            'type' => 'percent',
            'value' => 10,
            'min_order_value' => 0,
            'is_active' => true,
        ]);

        $this->post(route('cart.coupon.apply'), ['code' => 'giam10'])->assertRedirect();

        $this->post(route('checkout.store'), [
            'customer_name' => 'Khách',
            'phone' => '0900111222',
            'address' => '12 Lê Lợi',
            'fulfillment' => 'delivery',
            'payment_method' => 'cod',
        ]);

        $order = Order::first();
        $this->assertSame(30000, $order->subtotal);
        $this->assertSame(3000, $order->discount);
        $this->assertSame(15000, $order->shipping_fee);
        $this->assertSame(42000, $order->total);
        $this->assertNull($order->user_id);
    }

    public function test_mien_phi_ship_khi_tam_tinh_truoc_giam_dat_nguong(): void
    {
        $product = $this->product(150000, false);
        $this->post(route('cart.add'), [
            'product_id' => $product->id,
            'quantity' => 1,
            'spice' => 'none',
        ]);

        Coupon::create([
            'code' => 'GIAM20',
            'type' => 'fixed',
            'value' => 20000,
            'min_order_value' => 0,
            'is_active' => true,
        ]);
        $this->post(route('cart.coupon.apply'), ['code' => 'GIAM20']);

        $this->post(route('checkout.store'), [
            'customer_name' => 'Khách',
            'phone' => '0900111222',
            'address' => '12 Lê Lợi',
            'fulfillment' => 'delivery',
            'payment_method' => 'cod',
        ]);

        $order = Order::first();
        $this->assertSame(150000, $order->subtotal);
        $this->assertSame(20000, $order->discount);
        $this->assertSame(0, $order->shipping_fee);
        $this->assertSame(130000, $order->total);
    }

    public function test_nhan_tai_quan_khong_bat_dia_chi_va_phi_ship_bang_0(): void
    {
        $product = $this->product(25000);
        $this->addDrink($product);

        $this->post(route('checkout.store'), [
            'customer_name' => 'Khách',
            'phone' => '0900111222',
            'fulfillment' => 'takeaway',
            'payment_method' => 'cod',
        ])->assertRedirect(route('checkout.success'));

        $order = Order::first();
        $this->assertSame('takeaway', $order->fulfillment);
        $this->assertNull($order->address);
        $this->assertSame(0, $order->shipping_fee);
        $this->get(route('checkout.success'))->assertOk()->assertSee('Nhận tại quán');
    }

    public function test_giao_hang_thieu_dia_chi_bi_tu_choi(): void
    {
        $product = $this->product();
        $this->addDrink($product);

        $this->post(route('checkout.store'), [
            'customer_name' => 'Khách',
            'phone' => '0900111222',
            'fulfillment' => 'delivery',
            'payment_method' => 'cod',
        ])->assertSessionHasErrors('address');

        $this->assertSame(0, Order::count());
    }

    public function test_ma_khong_du_dieu_kien_khong_duoc_dat_hang(): void
    {
        $product = $this->product(25000);
        $this->addDrink($product);

        Coupon::create([
            'code' => 'TOITHIEU',
            'type' => 'fixed',
            'value' => 5000,
            'min_order_value' => 100000,
            'is_active' => true,
        ]);

        $this->post(route('cart.coupon.apply'), ['code' => 'TOITHIEU'])
            ->assertSessionHas('error');
    }

    public function test_tra_cuu_dung_sdt_va_huy_khi_pending(): void
    {
        $product = $this->product();
        $this->addDrink($product);
        $this->post(route('checkout.store'), [
            'customer_name' => 'Khách',
            'phone' => '0900111222',
            'fulfillment' => 'takeaway',
            'payment_method' => 'vietqr',
        ]);

        $order = Order::first();
        config([
            'services.vietqr.bank_id' => '970422',
            'services.vietqr.account_no' => '0123456789',
            'services.vietqr.account_name' => 'NGUYEN VAN A',
        ]);

        $this->post(route('track.store'), [
            'order_code' => strtolower($order->order_code),
            'phone' => '0999999999',
        ])->assertSessionHas('error');

        $this->post(route('track.store'), [
            'order_code' => $order->order_code,
            'phone' => '0900111222',
        ])->assertRedirect(route('track.result'));

        $this->get(route('track.result'))
            ->assertOk()
            ->assertSee('img.vietqr.io', false)
            ->assertSee($order->order_code)
            ->assertSee('Nội dung chuyển khoản mặc định là mã đơn hàng');

        $this->post(route('track.cancel'), [
            'order_code' => $order->order_code,
            'phone' => '0900111222',
        ])->assertRedirect(route('track.result'));

        $this->assertSame('cancelled', $order->fresh()->status);

        $this->post(route('track.cancel'), [
            'order_code' => $order->order_code,
            'phone' => '0900111222',
        ])->assertSessionHas('error');
    }

    public function test_khach_co_tai_khoan_huy_don_confirmed_va_khong_huy_khi_dang_pha_che(): void
    {
        $user = User::create([
            'name' => 'Khách',
            'email' => 'khach' . uniqid() . '@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);
        $product = $this->product();
        $this->actingAs($user);
        $this->addDrink($product);
        $this->post(route('checkout.store'), [
            'customer_name' => 'Khách',
            'phone' => '0900111222',
            'address' => '12 Lê Lợi',
            'fulfillment' => 'delivery',
            'payment_method' => 'cod',
        ]);

        $order = Order::first();
        $this->assertSame($user->id, $order->user_id);

        $order->update(['status' => 'confirmed']);
        $this->post(route('orders.cancel', $order->order_code))->assertRedirect();
        $this->assertSame('cancelled', $order->fresh()->status);

        $order->update(['status' => 'preparing']);
        $this->post(route('orders.cancel', $order->order_code))->assertSessionHas('error');
        $this->assertSame('preparing', $order->fresh()->status);
    }

    public function test_checkout_chan_mon_vua_het_hang(): void
    {
        $product = $this->product(25000, false);
        $this->post(route('cart.add'), [
            'product_id' => $product->id,
            'quantity' => 1,
            'spice' => 'none',
        ]);
        $product->update(['is_available' => false]);

        $this->post(route('checkout.store'), [
            'customer_name' => 'Khách',
            'phone' => '0900111222',
            'address' => '12 Lê Lợi',
            'fulfillment' => 'delivery',
            'payment_method' => 'cod',
        ])->assertRedirect(route('cart.index'))
            ->assertSessionHas('error', 'Món ' . $product->name . ' hiện vừa hết hàng, vui lòng xóa khỏi giỏ để tiếp tục.');

        $this->assertSame(0, Order::count());
    }
}
