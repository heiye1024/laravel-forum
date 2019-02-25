<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CalculateActiveUser extends Command
{

    // 供我們調用命令
    protected $signature = 'laravalforum:calculate-active-user';

    // 命令的描述
    protected $description = '產生活躍使用者';

    // 最終執行的方法
    public function handle(User $user)
    {
        // 在命令行打印一行訊息
        $this->info('開始計算...');
        $user->calculateAndCacheActiveUsers();
        $this->info('成功產生');
    }
}
