<?php

return [
    'name' => 'Front',
    'logo_width' => 150,
    'product_url_prefix' => get_product_prefix(),
    'shipping_cost' => 0,
    'shipping_cost_large_weight' => 144000,
    'default_online_gateway' => 'zibal',
    'gateway' => [
        'zibal' => [
            'merchantID' => get_zibal_merchant_id(),
            'requestPaymentUrl' => env('REQUEST_PAYMENT_URL'),
            'startPaymentUrl' => env('START_PAYMENT_URL'),
            'verifyPaymentUrl' => env('VERIFY_PAYMENT_URL'),
            'api_key' => get_zibal_api_key(),
            'wallet_id' => get_zibal_wallet_id(),
        ],
        'fee' => 0.01,
        'max_fee' => 4000
    ],
    'notification' => [
        'telegram' => [
            'chat_id' => env('TELEGRAM_CHAT_ID',''),
            'bot_token' => env('TELEGRAM_BOT_TOKEN',''),
            'proxy' => env('TELEGRAM_PROXY',''),
        ]
    ]
];
