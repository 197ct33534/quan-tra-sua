<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class FetchProductImages extends Command
{
    protected $signature = 'products:fetch-images
        {--all : Tải lại cho cả sản phẩm đã có ảnh (mặc định chỉ sản phẩm chưa có)}
        {--per-page=30 : Số ảnh lấy về mỗi danh mục để gán không trùng}';

    protected $description = 'Tải ảnh stock từ Pexels và gán cho sản phẩm theo danh mục';

    /** Từ khóa tìm ảnh (tiếng Anh cho kết quả tốt) theo slug danh mục */
    protected array $queries = [
        'tra-sua' => 'bubble tea milk tea drink',
        'banh-trang-tron' => 'vietnamese street food snack',
        'banh-trang-nuong' => 'vietnamese grilled rice paper food',
    ];

    public function handle(): int
    {
        $key = config('services.pexels.key');
        if (empty($key)) {
            $this->error('Chưa có PEXELS_API_KEY trong .env. Lấy key miễn phí tại https://www.pexels.com/api/');
            return self::FAILURE;
        }

        Storage::disk('public')->makeDirectory('products');

        $categories = Category::with(['products' => function ($q) {
            if (! $this->option('all')) {
                $q->whereNull('image');
            }
        }])->get();

        $totalDone = 0;

        foreach ($categories as $category) {
            $products = $category->products;
            if ($products->isEmpty()) {
                continue;
            }

            $query = $this->queries[$category->slug] ?? ($category->name . ' food');
            $this->info("Danh mục \"{$category->name}\" → tìm: {$query} ({$products->count()} SP)");

            $response = Http::withHeaders(['Authorization' => $key])
                ->get('https://api.pexels.com/v1/search', [
                    'query' => $query,
                    'per_page' => (int) $this->option('per-page'),
                    'orientation' => 'landscape',
                ]);

            if (! $response->successful()) {
                $this->error("  Lỗi Pexels API ({$response->status()}): " . $response->body());
                continue;
            }

            $photos = collect($response->json('photos', []));
            if ($photos->isEmpty()) {
                $this->warn("  Không tìm thấy ảnh cho \"{$query}\".");
                continue;
            }

            foreach ($products->values() as $i => $product) {
                // Gán ảnh khác nhau cho từng sản phẩm trong danh mục (xoay vòng nếu thiếu)
                $photo = $photos[$i % $photos->count()];
                $src = $photo['src']['large'] ?? $photo['src']['medium'] ?? $photo['src']['original'] ?? null;
                if (! $src) {
                    continue;
                }

                $img = Http::get($src);
                if (! $img->successful()) {
                    $this->warn("  Tải ảnh thất bại cho: {$product->name}");
                    continue;
                }

                // Xóa ảnh cũ nếu có
                if ($product->image && Storage::disk('public')->exists($product->image)) {
                    Storage::disk('public')->delete($product->image);
                }

                $path = 'products/' . $product->slug . '-' . $photo['id'] . '.jpg';
                Storage::disk('public')->put($path, $img->body());
                $product->update(['image' => $path]);

                $this->line("  ✓ {$product->name}  ←  Pexels #{$photo['id']} (ảnh bởi {$photo['photographer']})");
                $totalDone++;
            }
        }

        $this->newLine();
        $this->info("Hoàn tất! Đã gán ảnh cho {$totalDone} sản phẩm.");
        $this->comment('Ảnh từ Pexels.com — miễn phí dùng theo Pexels License.');

        return self::SUCCESS;
    }
}
