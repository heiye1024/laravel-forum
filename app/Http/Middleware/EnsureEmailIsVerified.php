<?php

namespace App\Http\Middleware;

use Closure;

class EnsureEmailIsVerified
{
    public function handle($request, Closure $next)
    {
        // 三個判斷：
        // 1. 如果使用者已經登入
        // 2. 並且還未認證Email
        // 3. 並且訪問的不是Email驗證相關的URL或者退出的URL

        if($request->user() &&
            ! $request->user()->hasVerifiedEmail() &&
            ! $request->is('email/*', 'logout')) {

            // 根據Client端返回對應的內容
            return $request->expectsJson()
                    ? abort(403, "你的Email尚未認證")
                    : redirect()->route('verification.notice');
        }

        return $next($request);
    }
}
