<?php

namespace App\Providers;

use App\Services\CartService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CartService::class);
    }

    public function boot(): void
    {
        // Storefront dùng Tailwind (mặc định của Laravel); admin gọi view bootstrap-5 riêng.

        // Định dạng tiền VND: @vnd($amount) => 25.000đ
        Blade::directive('vnd', function ($expr) {
            return "<?php echo number_format((int) ($expr), 0, ',', '.') . 'đ'; ?>";
        });

        // Chia sẻ số lượng item trong giỏ hàng cho mọi view
        View::composer('*', function ($view) {
            $view->with('cartCount', app(CartService::class)->count());
        });
    }
}
