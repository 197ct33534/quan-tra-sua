<?php

$address = env('SHOP_ADDRESS', '15 Lê Thánh Tôn, Phường Bến Nghé, Quận 1, TP. Hồ Chí Minh');
$phone = env('SHOP_PHONE', '028 3822 4468');
$digits = preg_replace('/\D+/', '', (string) $phone);

return [

    'phone' => $phone,
    'address' => $address,
    'hours' => env('SHOP_HOURS', '08:00–22:00 mỗi ngày'),

    'facebook' => env('SHOP_FACEBOOK', 'https://www.facebook.com/search/top?q='.rawurlencode((string) env('APP_NAME', 'Quán Trà Sữa'))),
    'instagram' => env('SHOP_INSTAGRAM', 'https://www.instagram.com/explore/tags/trasua/'),
    'zalo' => env('SHOP_ZALO', 'https://zalo.me/'.$digits),
    'maps' => env('SHOP_MAPS', 'https://www.google.com/maps/search/?api=1&query='.rawurlencode($address)),

];
