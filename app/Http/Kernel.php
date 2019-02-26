<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    // 全局中間件
    protected $middleware = [
        // 檢查應用是否進入「維護模式」
        // 查laravel/5.7/configuration#maintenance-mode
        \App\Http\Middleware\CheckForMaintenanceMode::class,

        // 檢查表單請求的數據是否過大
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,

        // 對提交的請求參數進行 PHP 函數 `trim()` 處理
        \App\Http\Middleware\TrimStrings::class,

        // 將提交請求參數中空字串轉換為 null
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,

        // 修正代理服務器後的 Server 參數
        \App\Http\Middleware\TrustProxies::class,
    ];

    protected $middlewareGroups = [
        // Web 中間件陣列，應用於 routes/web.php 路由文件
        // 在 RouteServiceProvider 中設定
        'web' => [
            // Cookie 加密解密
            \App\Http\Middleware\EncryptCookies::class,

            // 將 Cookie 增加到回應中
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,

            // 開啟 Session
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,

            // 將系統的錯誤數據注入到 View 變數 $errors 中
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,

            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,

            // 強制使用者電子郵件認證
            \App\Http\Middleware\EnsureEmailIsVerified::class,

            // 記錄使用者最後登入時間
            \App\Http\Middleware\RecordLastActivedTime::class,
        ],

        // API 中間陣列中，應用於 routes/api.php 路由文件
        // 在 RouteServiceProvider 中設定
        'api' => [
            // 使用別名來調用中間件
            // 查laravel/5.7/middleware#为路由分配中间件
            'throttle:60,1',
            'bindings',
        ],
    ];

    // 中間件別名設置，允許你使用別名調用中間件，例如上面的 api 中間件陣列調用
    protected $routeMiddleware = [

        // 只有登入使用者才能訪問，我們在控制器的建構方法中大量使用
        'auth' => \App\Http\Middleware\Authenticate::class,

        // HTTP Basic Auth 認證
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,

        // 處理路由綁定
        // 查laravel/5.7/routing#route-model-binding
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,

        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,

        // 使用者授權功能
        'can' => \Illuminate\Auth\Middleware\Authorize::class,

        // 只有訪客才能訪問，在 register 和 login 請求中使用，只有未登入使用者才能訪問這些頁面
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,

        // 簽名認證，在找回密碼的時候會使用到
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,

        // 訪問節流，類似於「1分鐘只能請求10次」的需求，一般在 API 中使用
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,

        // Laravel 自帶的強制使用者電子郵件認證的中間件，為了更貼近我們的邏輯，已經在之前被重寫
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
    ];

    // 設定中間件優先級，此陣列定義了除「全局中間件」以外的中間件執行順序
    // 可以看到 StartSession 永遠是最開始執行的，因為 StartSession 後
    // 我們才能在程式中使用 Auth 等使用者認證的功能
    protected $middlewarePriority = [
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\Authenticate::class,
        \Illuminate\Session\Middleware\AuthenticateSession::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        \Illuminate\Auth\Middleware\Authorize::class,
    ];
}
