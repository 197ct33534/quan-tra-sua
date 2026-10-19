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

    public function test_khong_nhay_coc_trang_thai_don(): void
    {
        $admin = $this->user('admin');
        $order = Order::create([
            'order_code' => 'ORD-TEST-0001',
            'user_id' => $this->user('customer')->id,
            'customer_name' => 'A',
            'phone' => '0900000000',
            'address' => '123',
            'subtotal' => 25000,
            'shipping_fee' => 15000,
            'total' => 40000,
            'payment_method' => 'cod',
            'status' => 'pending',
        ]);

        $this->actingAs($admin)
            ->put(route('admin.orders.update', $order), ['status' => 'completed'])
            ->assertSessionHas('error');

        $this->assertSame('pending', $order->fresh()->status);
    }

    public function test_staff_xem_don_in_bill_va_khong_sua_danh_muc(): void
    {
        $staff = $this->user('staff');
        $order = Order::create([
            'order_code' => 'ORD-TEST-0002',
            'user_id' => $this->user('customer')->id,
            'customer_name' => 'A',
            'phone' => '0900000000',
            'fulfillment' => 'takeaway',
            'subtotal' => 25000,
            'shipping_fee' => 0,
            'total' => 25000,
            'payment_method' => 'cod',
            'status' => 'pending',
        ]);
        $order->items()->create([
            'product_name' => 'Trà sữa',
            'unit_price' => 25000,
            'quantity' => 1,
            'size' => 'L',
            'sugar' => 50,
            'ice' => 70,
            'line_total' => 25000,
        ]);

        $this->actingAs($staff);
        $this->get(route('admin.orders.index'))->assertOk()->assertSee('Bật chuông thông báo');
        $this->get(route('admin.orders.check'))->assertOk()->assertJsonStructure(['pending_count', 'latest_id', 'new_count']);
        $this->get(route('admin.orders.print', $order))
            ->assertOk()
            ->assertSee('80mm', false)
            ->assertSee('Size L')
            ->assertSee('50% đường')
            ->assertSee('Nhận tại quán')
            ->assertSee('window.print()', false);
        $this->get(route('admin.categories.index'))->assertForbidden();
        $this->get(route('admin.products.create'))->assertForbidden();
    }

    public function test_staff_bat_tat_con_hang(): void
    {
        $staff = $this->user('staff');
        $category = Category::create(['name' => 'Trà sữa', 'slug' => 'ts-' . uniqid()]);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Matcha',
            'slug' => 'matcha-' . uniqid(),
            'price' => 30000,
            'is_available' => true,
        ]);

        $this->actingAs($staff)
            ->postJson(route('admin.products.availability', $product), ['available' => false])
            ->assertOk()
            ->assertJson(['is_available' => false]);

        $this->assertFalse($product->fresh()->is_available);
    }
}
