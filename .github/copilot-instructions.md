# Copilot instructions for this repo

## Project shape
- This is a Laravel 12 + PHP 8.2 storefront/admin app for a bubble tea shop, not a SPA. Core routing is in `routes/web.php`; storefront and admin are Blade views, with `auth` and `staff` middleware splitting access.
- Main business flow is cart -> checkout -> order tracking/admin workflow. See `app/Services/CartService.php`, `app/Services/PricingService.php`, `app/Http/Controllers/CheckoutController.php`, and `app/Models/Order.php`.
- Do not introduce Inertia/Livewire/React/Vue/Filament/Sanctum/Redis or new payment SDKs unless explicitly requested; this repo intentionally uses Laravel Blade + session cart.

## Key business rules
- Prices are stored as integers in VND; formatting is via `@vnd($amount)` in `AppServiceProvider` and is the canonical display format.
- `CartService` is the source of truth for cart row identity and price snapshots. A row is keyed by `product_id + size + sugar + ice + spice + sorted topping IDs`, so same item with different options is a different line.
- Drink items (`has_options`) require `size`, `sugar`, and `ice`; snack items use `spice` instead. Topping prices are added to the unit price and saved in `order_items` as snapshot data.
- `PricingService` handles coupon validation and shipping: shipping is `0` for takeaway, `0` when subtotal >= 150,000 for delivery, otherwise `15,000`; coupon discount does not reduce shipping.
- `Order` status flow is `pending -> confirmed -> preparing -> delivering -> completed`; cancellations are only allowed for `pending` or `confirmed`.

## Data and integration patterns
- Cart and coupon state live in the session (`cart`, `coupon_code`, `tracked_order`, `recent_order`), not in a DB cart table.
- Checkout recomputes subtotal and quote from current session cart and then writes order snapshot rows to `orders` and `order_items` inside a DB transaction.
- Guest checkout is supported; `user_id` is nullable and guest order lookup is by `order_code + phone`.
- Admin operations are split roughly by role: `admin` can CRUD categories/products/toppings/coupons; `staff` can view/update orders and toggle availability.

## UI conventions
- Storefront views extend `resources/views/layouts/app`, admin views extend `resources/views/layouts/admin`.
- Labels and option display should come from model constants/helpers, not hardcoded price assumptions in Blade. Example: `OrderItem::sugarLabel()` / `iceLabel()` and `product_sizes` fee map.
- The print bill is a dedicated print view with `@media print`; admin polling checks for new orders every ~20s and only plays the bell after user interaction.

## Workflow and commands
- Setup: `composer install`, copy `.env.example` to `.env` if needed, `php artisan key:generate`, `php artisan migrate`, `npm install`, then `npm run build`.
- Local app: `php artisan serve` for the Laravel app, and `npm run dev` for Vite assets; `composer run dev` starts both plus queue and pail in one command.
- Tests: use `php artisan test` (or `composer test`); focus feature tests around cart/checkout/coupon/shipping and admin role rules.
- Keep validation in `app/Http/Requests` with Vietnamese attributes and redirect with `success`/`error` flashes instead of silently swallowing exceptions.

## Examples to follow
- The correct pattern for cart logic is `CartService::add()` / `update()` / `subtotal()` rather than re-deriving totals in controllers.
- The correct pattern for order pricing is `PricingService::quote($subtotal, $fulfillment)` rather than hardcoded fee math inside blade or controller methods.
- The correct pattern for product size is `Product::sizes()` / `product_sizes` lookup, not inline `if ($size === 'M')` price booleans in views.
