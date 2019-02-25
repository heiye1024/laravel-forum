<?php

return array(

    // 後台的URI入口
    'uri' => 'admin',

    // 後台專屬域名，沒有的話可以留空
    'domain' => '',

    // 應用名稱，在頁面標題和左上角網站名稱處顯示
    'title' => env('APP_NAME', 'Laravel'),

    // 模型配置資料文件存放目錄
    'model_config_path' => config_path('administrator'),

    // 配置資料文件存放目錄
    'settings_config_path' => config_path('administrator/settings'),

    /*
    後台選單陣列，多維陣列渲染結果為多層的選單
    陣列裡的值有三種類型：
    1. 字串：子菜單的入口，不可訪問
    2. 模型配置文件：訪問`model_config_path`目錄下的模型文件，如`users`訪問的是`users.php`模型配置文件
    3. 配置文件：必須使用前缀 `settings.`，對應`settings_config_path`目錄下的文件，如默認設置下，
               `settings.site`訪問的是`administrator/settings/site.php`文件
    4. 頁面文件：必須使用前缀`page.`，如：`page.pages.analytics`對應的是`administrator/pages/analytics.php`或者是
               `administrator/pages/analytics.blade.php`，兩種後缀名稱皆可
    配置範例
    [
        'users',
        'E-Commerce' => ['collections', 'products', 'product_images', 'orders'],
        'Settings'  => ['settings.site', 'settings.ecommerce', 'settings.social'],
        'Analytics' => ['E-Commerce' => 'page.pages.analytics'],
    ]
    */
    'menu' => [
        '使用者與權限' => [
            'users',
            'roles',
            'permissions',
        ],
        '內容管理' => [
            'categories',
            'topics',
            'replies',
        ],
        '站內管理' => [
            'settings.site',
            'links',
        ],
    ],

    /*
    權限控制的回調函數

    此回調函數需要返回 true 或 false，用來檢測當前使用者是否有權限訪問後台
    `true` 為通過，`false`會將頁面重定向到`login_path`選項定義的URL中
    */
    'permission' => function () {
        // 只要是能管理內容的使用者，就允許訪問後台
        return Auth::check() && Auth::user()->can('manage_contents');
    },

    /*
    使用布林值來設定是否使用後台主頁面
    如值為`true`，將使用`dashboard_view`定義的view文件渲染頁面
    如值為`false`，將使用`home_page`定義的選單條目來作為後台主頁
    */
    'use_dashboard' => false,

    // 設置後台主頁view文件，由`use_dashboard`選項決定
    'dashboard_view' => '',

    // 用來作為後台主頁的選單，由`use_dashboard`選項決定，選單指的是`menu`選項
    'home_page' => 'topics',

    // 右上角「返回主網站」按鈕的連結
    'back_to_site_path' => '/',

    // 當選項`permission`權限檢測不通過時，會重定向使用者到此處設置的路徑
    'login_path' => 'permission-denied',

    // 允許在登入成功後使用 Session::get('redirect')將使用者重定向到原本想要訪問的後台頁面
    'login_redirect_key' => 'redirect',

    // 控制模型數據列表頁默認的顯示條目
    'global_rows_per_page' => 20,

    // 可選的語言，如果不為空，將會在頁面頂部顯示「選擇語言」按鈕
    'locales' => [],
);
