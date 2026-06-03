<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderFlowTest extends TestCase
{
    use RefreshDatabase;

    private function customer(): User
    {
        return User::create([
            'name' => 'Khách Test',
            'email' => 'khach' . uniqid() . '@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);
    }

    private function product(int $price = 25000): Product
    {
        $category = Category::create(['name' => 'Trà sữa', 'slug' => 'tra-sua-' . uniqid()]);

        return Product::create([
            'category_id' => $category->id,
            'name' => 'Trà sữa truyền thống',
            'slug' => 'sp-' . uniqid(),
            'price' => $price,
            'has_options' => true,
            'is_available' => true,
        ]);
    }

    public function test_khach_chua_dang_nhap_bi_chuyen_ve_login_khi_checkout(): void
    {
        $this->get('/thanh-toan')->assertRedirect(route('login'));
    }

    public function test_them_san_pham_vao_gio(): void
    {
        $product = $this->product();

        $this->post(route('cart.add'), [
            'product_id' => $product->id,
            'quantity' => 2,
            'size' => 'M',
        ])->assertRedirect(route('cart.index'));

        $this->get(route('cart.index'))
            ->assertOk()
            ->assertSee($product->name);
    }

    public function test_dat_hang_thanh_cong_tao_don_va_xoa_gio(): void
    {
        $user = $this->customer();
        $product = $this->product(25000);

        $this->actingAs($user);

        // thêm 2 ly size M (25000 + 5000) = 30000/ly => subtotal 60000
        $this->post(route('cart.add'), [
            'product_id' => $product->id,
            'quantity' => 2,
            'size' => 'M',
        ]);

        $this->get(route('checkout.index'))->assertOk();

        $response = $this->post(route('checkout.store'), [
            'customer_name' => 'Nguyễn Văn A',
            'phone' => '0900000000',
            'address' => '123 Đường ABC',
            'note' => 'Giao nhanh',
        ]);

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'customer_name' => 'Nguyễn Văn A',
            'subtotal' => 60000,
            'shipping_fee' => 15000,
            'total' => 75000,
            'status' => 'pending',
            'payment_method' => 'cod',
        ]);

        $this->assertDatabaseHas('order_items', [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'size' => 'M',
            'quantity' => 2,
            'line_total' => 60000,
        ]);

        // order_code đúng định dạng TS + ngày + 4 ký tự
        $order = \App\Models\Order::first();
        $this->assertMatchesRegularExpression('/^TS\d{6}[A-Z0-9]{4}$/', $order->order_code);

        // redirect tới trang theo dõi đơn
        $response->assertRedirect(route('orders.show', $order->order_code));

        // giỏ đã được xóa
        $this->get(route('checkout.index'))->assertRedirect(route('cart.index'));
    }

    public function test_checkout_validation_bat_buoc_thong_tin(): void
    {
        $user = $this->customer();
        $product = $this->product();
        $this->actingAs($user);
        $this->post(route('cart.add'), ['product_id' => $product->id, 'quantity' => 1, 'size' => 'M']);

        $this->post(route('checkout.store'), [])
            ->assertSessionHasErrors(['customer_name', 'phone', 'address']);
    }

    public function test_khach_xem_duoc_don_cua_minh(): void
    {
        $user = $this->customer();
        $product = $this->product();
        $this->actingAs($user);
        $this->post(route('cart.add'), ['product_id' => $product->id, 'quantity' => 1, 'size' => 'M']);
        $this->post(route('checkout.store'), [
            'customer_name' => 'A', 'phone' => '0900000000', 'address' => '123',
        ]);

        $order = \App\Models\Order::first();
        $this->get(route('orders.show', $order->order_code))
            ->assertOk()
            ->assertSee($order->order_code);
    }
}
