<?php

namespace App\Models\Traits;

use Redis;
use Carbon\Carbon;

trait LastActivedAtHelper
{
    // 緩存相關
    protected $hash_prefix = 'laravelforum_last_actived_at_';
    protected $field_prefix = 'user_';

    public function recordLastActivedAt()
    {
        // Redis Hash 表的命名，如 laravelforum_last_actived_at_2019-02-26
        $hash = $this->getHashFromDateString(Carbon::now()->toDateString());

        // 欄位名稱，如 user_1
        $field = $this->getHashField();

        //dd(Redis::hGetAll($hash));

        // 當前時間，如 2019-02-26 03:57:30
        $now = Carbon::now()->toDateTimeString();

        // 數據寫入 Redis，欄位已存在會被更新
        Redis::hSet($hash, $field, $now);
    }

    public function syncUserActivedAt()
    {
        // Redis Hash 表的命名，如 laravelforum_last_actived_at_2019-02-26
        $hash = $this->getHashFromDateString(Carbon::yesterday()->toDateString());

        // 從 Redis 中獲取所有 Hash 表裡的數據
        $dates = Redis::hGetAll($hash);

        // 將所有數據同步到資料庫中
        foreach ($dates as $user_id => $actived_at) {
            // 會將 `user_1` 轉換為 1
            $user_id = str_replace($this->field_prefix, '', $user_id);

            // 只有當使用者存在時才更新到資料庫中
            if ($user = $this->find($user_id)) {
                $user->last_actived_at = $actived_at;
                $user->save();
            }
        }

        // 以資料庫為中心的儲存，已經同步，就可以刪除
        Redis::del($hash);
    }

    public function getLastActivedAtAttribute($value)
    {
        // 獲取今天對應的 Hash 表名稱
        $hash = $this->getHashFromDateString(Carbon::now()->toDateString());

        // 欄位名稱，如 user_1
        $field = $this->getHashField();

        // 三元運算符，優先選擇 Redis 的數據，否則使用資料庫中
        $datetime = Redis::hGet($hash, $field) ? : $value;

        // 如果存在的話，返回時間對應的 Carbon 實體
        if ($datetime) {
            return new Carbon($datetime);
        } else {
            // 否則使用使用者註冊時間
            return $this->created_at;
        }
    }

    public function getHashFromDateString($date)
    {
        // Redis Hash 表的命名，如：laravelforum_last_actived_at_2019-02-26
        return $this->hash_prefix . $date;
    }

    public function getHashField()
    {
        // 欄位名稱，如 user_1
        return $this->field_prefix . $this->id;
    }
}
