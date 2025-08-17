<?php

return [
    'name' => 'User',
    'otp' => [
        'expiration_period' => 300,
        'code_length' => 5,
    ],
    'sms' => [
        'url' => env('SMS_URL'),
        'api_key' => get_ippanel_api_key(),
        'pattern_id' => get_ippanel_pattern_id(),
        'send_from' => env('SMS_SEND_FROM'),
        'delivery_shipping_alert_pattern' => 'h7y865s0coa6p5m',
        'thank_you_after_sale_pattern' => 'fig7sbfpvpnwnff',
        'notification_order_status_pattern' => '5nsggqrikt7wkn6',
        'send_coupon_for_customer' => '3zptm8jpiftrblx',

    ],

];


