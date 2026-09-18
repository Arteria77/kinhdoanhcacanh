<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'vnpay' => [
        'tmn_code' => env('VNPAY_TMN_CODE', 'YOUR_TMN_CODE'),
        'hash_secret' => env('VNPAY_HASH_SECRET', 'YOUR_HASH_SECRET'),
        'url' => env('VNPAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'),
        'return_url' => env('VNPAY_RETURN_URL', '/payment/vnpay-return'),
    ],

    'ghn' => [
        'token' => env('GHN_TOKEN', 'eea1eb4a-aa85-11f1-a973-aee5264794df'),
        'shop_id' => env('GHN_SHOP_ID', 217505),
        'api_url' => env('GHN_API_URL', 'https://dev-online-gateway.ghn.vn/shiip/public-api'),
    ],

    'sepay' => [
        'api_token' => env('SEPAY_API_TOKEN', '2ZABJEF6XREZPY7VT5QNN0BQGCCEUIVGZIMFGMCWMSOYUOF7SIXWH89YETJ5NPYL'),
        'bank_brand' => env('SEPAY_BANK_BRAND', 'TPBank'),
        'account_number' => env('SEPAY_ACCOUNT_NUMBER', '12325072005'),
        'account_name' => env('SEPAY_ACCOUNT_NAME', 'HOANG NGOC THI'),
    ],

];
