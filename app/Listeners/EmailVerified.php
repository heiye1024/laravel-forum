<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Verified;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailVerified
{
    public function __construct()
    {
        //
    }

    public function handle(Verified $event)
    {
        // 認證成功後的提醒
        session()->flash('success', '電子信箱驗證成功');
    }
}
