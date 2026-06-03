<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Topping;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartServiceTest extends TestCase
{
    use RefreshDatabase;

    protected CartService $cart;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cart = app(CartService::class);
    }

    private function product(array $attrs = []): Product
    {
        $category = Category::create(['name' => 'Trà sữa', 'slug' => 'tra-sua-' . uniqid()]);

        return Product::create(array_merge([
            'category_id' => $category->id,
            'name' => 'Trà sữa truyền thống',
            'slug' => 'sp-' . uniqid(),
            'price' => 25000,
            'has_options' => false,
            'is_available' => true,
        ], $attrs));
    }

    public function test_gio_hang_ban_dau_rong(): void
    {
        $this->assertTrue($this->cart->isEmpty());
        $this->assertSame(0, $this->cart->count());
        $this->assertSame(0, $this->cart->subtotal());
    }

    public function test_them_san_pham_khong_co_tuy_chon(): void
    {
        $product = $this->product(['price' => 25000]);

        $this->cart->add($product, 2);

        $items = array_values($this->cart->items());
        $this->assertCount(1, $items);
        $this->assertSame(25000, $items[0]['unit_price']);
        $this->assertSame(50000, $items[0]['line_total']);
        $this->assertSame(2, $this->cart->count());
        $this->assertSame(50000, $this->cart->subtotal());
    }

    public function test_phi_size_M_va_L(): void
    {
        $product = $this->product(['price' => 25000, 'has_options' => true]);

        $this->cart->add($product, 1, 'M');
        $this->assertSame(30000, $this->cart->subtotal()); // 25000 + 5000

        $this->cart->clear();
        $this->cart->add($product, 1, 'L');
        $this->assertSame(35000, $this->cart->subtotal()); // 25000 + 10000
    }

    public function test_size_bi_bo_qua_khi_san_pham_khong_co_tuy_chon(): void
    {
        $product = $this->product(['price' => 25000, 'has_options' => false]);

        $this->cart->add($product, 1, 'L'); // size L nhưng SP không có option

        $items = array_values($this->cart->items());
        $this->assertNull($items[0]['size']);
        $this->assertSame(25000, $items[0]['unit_price']); // không cộng phí size
    }

    public function test_them_topping_cong_dung_gia(): void
    {
        $product = $this->product(['price' => 25000, 'has_options' => true]);
        $t1 = Topping::create(['name' => 'Trân châu', 'price' => 5000, 'is_active' => true]);
        $t2 = Topping::create(['name' => 'Pudding', 'price' => 7000, 'is_active' => true]);

        $this->cart->add($product, 1, 'M', [$t1->id, $t2->id]);

        // 25000 + size M 5000 + topping 5000 + 7000 = 42000
        $this->assertSame(42000, $this->cart->subtotal());
        $items = array_values($this->cart->items());
        $this->assertCount(2, $items[0]['toppings']);
    }

    public function test_them_cung_san_pham_size_topping_thi_gop_so_luong(): void
    {
        $product = $this->product(['price' => 25000, 'has_options' => true]);

        $this->cart->add($product, 1, 'M');
        $this->cart->add($product, 2, 'M');

        $this->assertCount(1, $this->cart->items());
        $this->assertSame(3, $this->cart->count());
    }

    public function test_khac_size_thi_tach_dong(): void
    {
        $product = $this->product(['price' => 25000, 'has_options' => true]);

        $this->cart->add($product, 1, 'M');
        $this->cart->add($product, 1, 'L');

        $this->assertCount(2, $this->cart->items());
    }

    public function test_cap_nhat_so_luong(): void
    {
        $product = $this->product(['price' => 25000]);
        $this->cart->add($product, 1);
        $rowId = array_key_first($this->cart->items());

        $this->cart->update($rowId, 4);
        $this->assertSame(4, $this->cart->count());
        $this->assertSame(100000, $this->cart->subtotal());
    }

    public function test_cap_nhat_so_luong_ve_0_thi_xoa_item(): void
    {
        $product = $this->product(['price' => 25000]);
        $this->cart->add($product, 2);
        $rowId = array_key_first($this->cart->items());

        $this->cart->update($rowId, 0);
        $this->assertTrue($this->cart->isEmpty());
    }

    public function test_xoa_va_clear(): void
    {
        $product = $this->product(['price' => 25000]);
        $this->cart->add($product, 1);
        $rowId = array_key_first($this->cart->items());

        $this->cart->remove($rowId);
        $this->assertTrue($this->cart->isEmpty());

        $this->cart->add($product, 1);
        $this->cart->clear();
        $this->assertTrue($this->cart->isEmpty());
    }
}
