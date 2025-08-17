<?php

return [
    'name' => 'داشبورد مدیریت',
    'short_shop_title' => 'اف‌دی‌استایل',
    'footer' => [
        'copy-right' => 'تمامی حقوق این سایت متعلق به ماست',
        'link1' => [
            'url' => 'https://rinofy.ir/?ref=auth_fdstyle',
            'text' => 'نوآوران فرداد',
        ],
    ],
    'route_prefix' => 'dashboard',
    'route_middleware' => ['web', 'auth:admin'],
    'route_as_name' => 'dashboard.',

 ];
