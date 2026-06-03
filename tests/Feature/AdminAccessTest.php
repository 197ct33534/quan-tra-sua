<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    private function user(string $role): User
    {
        return User::create([
            'name' => ucfirst($role),
            'email' => $role . uniqid() . '@test.com',
            'password' => bcrypt('password'),
            'role' => $role,
        ]);
    }

    public function test_khach_chua_dang_nhap_bi_chuyen_ve_login(): void
    {
        $this->get('/admin')->assertRedirect(route('login'));
    }

    public function test_khach_thuong_bi_chan_403(): void
    {
        $this->actingAs($this->user('customer'));
        $this->get('/admin')->assertForbidden();
        $this->get(route('admin.products.index'))->assertForbidden();
    }

    public function test_admin_vao_duoc_dashboard_va_cac_trang_quan_tri(): void
    {
        $this->actingAs($this->user('admin'));

        $this->get(route('admin.dashboard'))->assertOk();
        $this->get(route('admin.categories.index'))->assertOk();
        $this->get(route('admin.products.index'))->assertOk();
        $this->get(route('admin.toppings.index'))->assertOk();
        $this->get(route('admin.orders.index'))->assertOk();
    }

    public function test_admin_cap_nhat_trang_thai_don(): void
    {
        $admin = $this->user('admin');
        $customer = $this->user('customer');
        $category = Category::create(['name' => 'Trà sữa', 'slug' => 'ts-' . uniqid()]);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'TS', 'slug' => 'ts-' . uniqid(), 'price' => 25000,
        ]);
        $order = Order::create([
            'order_code' => 'TS260101ABCD',
            'user_id' => $customer->id,
            'customer_name' => 'A', 'phone' => '0900000000', 'address' => '123',
            'subtotal' => 25000, 'shipping_fee' => 15000, 'total' => 40000,
            'payment_method' => 'cod', 'status' => 'pending',
        ]);

        $this->actingAs($admin)
            ->put(route('admin.orders.update', $order), ['status' => 'confirmed'])
            ->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'confirmed',
        ]);
    }
}
