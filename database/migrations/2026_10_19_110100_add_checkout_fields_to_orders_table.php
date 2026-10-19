<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->change();
            $table->string('address')->nullable()->change();
            $table->string('fulfillment')->default('delivery')->after('address');
            $table->decimal('discount', 10, 0)->default(0)->after('subtotal');
            $table->string('coupon_code')->nullable()->after('discount');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['fulfillment', 'discount', 'coupon_code']);
        });
    }
};
