<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Topping;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // --- Tài khoản ---
        User::updateOrCreate(
            ['email' => 'admin@trasua.test'],
            [
                'name' => 'Quản trị viên',
                'phone' => '0900000001',
                'address' => 'Cửa hàng Trà Sữa',
                'role' => 'admin',
                'password' => Hash::make('password'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'staff@trasua.test'],
            [
                'name' => 'Nhân viên quầy',
                'phone' => '0900000003',
                'address' => 'Cửa hàng Trà Sữa',
                'role' => 'staff',
                'password' => Hash::make('password'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'khach@trasua.test'],
            [
                'name' => 'Khách Demo',
                'phone' => '0900000002',
                'address' => '123 Đường ABC, Quận 1',
                'role' => 'customer',
                'password' => Hash::make('password'),
            ]
        );

        // --- Topping ---
        $toppings = [
            ['name' => 'Trân châu đen', 'price' => 5000],
            ['name' => 'Trân châu trắng', 'price' => 5000],
            ['name' => 'Thạch phô mai', 'price' => 7000],
            ['name' => 'Pudding trứng', 'price' => 8000],
            ['name' => 'Kem cheese', 'price' => 10000],
            ['name' => 'Thạch dừa', 'price' => 5000],
        ];
        foreach ($toppings as $t) {
            Topping::updateOrCreate(['name' => $t['name']], $t + ['is_active' => true]);
        }

        // --- Danh mục + sản phẩm ---
        $data = [
            [
                'name' => 'Trà sữa',
                'description' => 'Trà sữa thơm béo, nhiều topping hấp dẫn',
                'has_options' => true,
                'products' => [
                    ['Trà sữa truyền thống', 25000, 'Vị trà sữa nguyên bản đậm đà.'],
                    ['Trà sữa trân châu đường đen', 32000, 'Trà sữa cùng trân châu đường đen dẻo thơm.'],
                    ['Trà sữa matcha', 35000, 'Matcha Nhật Bản hòa quyện sữa tươi.'],
                    ['Trà sữa khoai môn', 33000, 'Khoai môn bùi béo, màu tím đẹp mắt.'],
                    ['Hồng trà sữa', 28000, 'Hồng trà đậm vị kết hợp sữa.'],
                ],
            ],
            [
                'name' => 'Bánh tráng trộn',
                'description' => 'Bánh tráng trộn chua cay đậm đà vị Sài Gòn',
                'has_options' => false,
                'products' => [
                    ['Bánh tráng trộn đặc biệt', 25000, 'Đầy đủ topping: khô bò, trứng cút, xoài, rau răm.'],
                    ['Bánh tráng trộn sa tế', 20000, 'Cay nồng vị sa tế thơm lừng.'],
                    ['Bánh tráng trộn bơ', 22000, 'Béo ngậy vị bơ hành phi.'],
                    ['Bánh tráng trộn muối tôm', 18000, 'Đậm đà vị muối tôm Tây Ninh.'],
                ],
            ],
            [
                'name' => 'Bánh tráng nướng',
                'description' => 'Bánh tráng nướng giòn rụm kiểu Đà Lạt',
                'has_options' => false,
                'products' => [
                    ['Bánh tráng nướng thập cẩm', 30000, 'Trứng, xúc xích, phô mai, hành, pa-tê.'],
                    ['Bánh tráng nướng trứng', 20000, 'Giòn rụm với trứng và hành lá.'],
                    ['Bánh tráng nướng phô mai', 28000, 'Phô mai kéo sợi béo ngậy.'],
                    ['Bánh tráng nướng bò khô', 32000, 'Thêm khô bò đậm đà.'],
                ],
            ],
        ];

        foreach ($data as $i => $cat) {
            $category = Category::updateOrCreate(
                ['slug' => Str::slug($cat['name'])],
                [
                    'name' => $cat['name'],
                    'description' => $cat['description'],
                    'sort_order' => $i,
                    'is_active' => true,
                ]
            );

            foreach ($cat['products'] as $j => $p) {
                Product::updateOrCreate(
                    ['slug' => Str::slug($p[0])],
                    [
                        'category_id' => $category->id,
                        'name' => $p[0],
                        'price' => $p[1],
                        'description' => $p[2],
                        'is_available' => true,
                        'has_options' => $cat['has_options'],
                        'sort_order' => $j,
                    ]
                );
            }
        }

        Coupon::updateOrCreate(
            ['code' => 'KHAITRUONG'],
            [
                'type' => Coupon::TYPE_PERCENT,
                'value' => 10,
                'min_order_value' => 0,
                'expires_at' => null,
                'is_active' => true,
            ]
        );
    }
}
