<?php
return [
    // HTTP 請求的超過時間（秒）
    'timeout' => 5.0,

    // 默認發送配置
    'default' => [
        // 網站調用策略，默認：順序調用
        'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,

        // 默認可用的發送網站
        'gateways' => [
            'yunpian',
        ],
    ],

    // 可用的網站配置
    'gateways' => [
        'errorlog' => [
            'file' => '/tmp/easy-sms.log',
        ],
        'yunpian' => [
            'api_key' => env('YUNPIAN_API_KEY'),
        ],
    ],
];
