<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class GenerateToken extends Command
{

    protected $signature = 'laravelforum:generate-token';

    protected $description = '快速為使用者產生token';

    public function __construct()
    {
        parent::__construct();
    }

    // 輸入使用者 ID，查詢ID對應的使用者，然後為該使用者產生一個有效期限為1年的token
    public function handle()
    {
        $userId = $this->ask('輸入使用者 id');
        $user = User::find($userId);

        if (!$user) {
            return $this->error('使用者不存在');
        }

        // 一年以後過期
        $ttl = 365*24*60;
        $this->info(\Auth::guard('api')->setTTL($ttl)->fromUser($user));
    }
}
