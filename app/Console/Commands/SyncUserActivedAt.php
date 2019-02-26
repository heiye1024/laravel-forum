<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class SyncUserActivedAt extends Command
{
    protected $signature = 'laravelforum:sync-user-actived-at';
    protected $description = '將使用者最後登入時間從 Redis 同步到資料庫中';

    public function handle(User $user)
    {
        $user->syncUserActivedAt();
        $this->info("同步成功");
    }
}
