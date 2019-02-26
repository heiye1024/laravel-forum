<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class RecordLastActivedTime
{
    public function handle($request, Closure $next)
    {
        // 如果是登入使用者的話
        if (Auth::check()) {
            // 紀錄最後登入時間
            Auth::user()->RecordLastActivedAt();
        }

        return $next($request);
    }
}
