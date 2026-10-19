<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_sizes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('code', 8);
            $table->string('label');
            $table->decimal('price', 10, 0)->default(0);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['product_id', 'code']);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->unsignedTinyInteger('sugar')->nullable()->after('size');
            $table->unsignedTinyInteger('ice')->nullable()->after('sugar');
            $table->string('spice', 20)->nullable()->after('ice');
        });

        // Đơn cũ giữ null. Đồ uống đang bán nhận đúng phí size đang hardcode.
        $now = now();
        $defaults = [
            ['S', 'Nhỏ (S)', 0, 0],
            ['M', 'Vừa (M)', 5000, 1],
            ['L', 'Lớn (L)', 10000, 2],
        ];

        $drinkIds = DB::table('products')->where('has_options', true)->pluck('id');
        foreach ($drinkIds as $productId) {
            foreach ($defaults as [$code, $label, $price, $sort]) {
                DB::table('product_sizes')->insert([
                    'product_id' => $productId,
                    'code' => $code,
                    'label' => $label,
                    'price' => $price,
                    'sort_order' => $sort,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['sugar', 'ice', 'spice']);
        });

        Schema::dropIfExists('product_sizes');
    }
};
