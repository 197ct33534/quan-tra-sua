<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Topping;
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

    public function test_khach_vanglai_vao_duoc_trang_thanh_toan(): void
    {
        $product = $this->product();

        $this->post(route('cart.add'), [
            'product_id' => $product->id,
            'quantity' => 1,
            'size' => 'M',
        ]);

        $this->get(route('checkout.index'))->assertOk();
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
            'fulfillment' => 'delivery',
            'payment_method' => 'cod',
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
        $this->assertMatchesRegularExpression('/^ORD-\d{8}-[A-Z0-9]{4}$/', $order->order_code);

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

        $this->post(route('checkout.store'), [
            'fulfillment' => 'delivery',
            'payment_method' => 'cod',
        ])->assertSessionHasErrors(['customer_name', 'phone', 'address']);
    }

    public function test_khach_xem_duoc_don_cua_minh(): void
    {
        $user = $this->customer();
        $product = $this->product();
        $this->actingAs($user);
        $this->post(route('cart.add'), ['product_id' => $product->id, 'quantity' => 1, 'size' => 'M']);
        $this->post(route('checkout.store'), [
            'customer_name' => 'A',
            'phone' => '0900000000',
            'address' => '123',
            'fulfillment' => 'delivery',
            'payment_method' => 'cod',
        ]);

        $order = \App\Models\Order::first();
        $this->get(route('orders.show', $order->order_code))
            ->assertOk()
            ->assertSee($order->order_code);
    }

    public function test_trang_do_uong_render_size_tu_db_va_duong_da(): void
    {
        $product = $this->product();
        $product->sizes()->where('code', 'L')->update(['price' => 12000]);

        $this->get(route('products.show', $product->slug))
            ->assertOk()
            ->assertSee('data-fee="12000"', false)
            ->assertSee('name="sugar"', false)
            ->assertSee('name="ice"', false)
            ->assertSee('Không đường')
            ->assertSee('Không đá')
            ->assertDontSee('name="spice"', false)
            ->assertSee('property="og:title"', false)
            ->assertSee('property="og:image"', false)
            ->assertSee($product->name.' — 25.000đ', false)
            ->assertSee('id="sticky-price"', false)
            ->assertDontSee('cdn.tailwindcss.com', false);
    }

    public function test_trang_banh_trang_render_muc_cay(): void
    {
        $product = $this->product();
        $product->update(['has_options' => false]);

        $this->get(route('products.show', $product->slug))
            ->assertOk()
            ->assertSee('name="spice"', false)
            ->assertSee('Cay vừa')
            ->assertSee('Không cay')
            ->assertDontSee('name="sugar"', false);
    }

    public function test_gio_hang_in_tom_tat_bien_the(): void
    {
        $product = $this->product();
        $topping = \App\Models\Topping::create([
            'name' => 'Trân châu trắng',
            'price' => 5000,
            'is_active' => true,
        ]);

        $this->post(route('cart.add'), [
            'product_id' => $product->id,
            'quantity' => 1,
            'size' => 'L',
            'sugar' => 50,
            'ice' => 70,
            'toppings' => [$topping->id],
        ])->assertRedirect(route('cart.index'));

        $this->get(route('cart.index'))
            ->assertOk()
            ->assertSee('Size L | 50% Đường | 70% Đá | Topping Trân châu trắng')
            ->assertSee('name="row_id"', false);
    }
}
