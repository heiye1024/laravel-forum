<?php

namespace App\Models\Traits;

use App\Models\Topic;
use App\Models\Reply;
use Carbon\Carbon;
use Cache;
use DB;

trait ActiveUserHelper
{
    // 用來存放臨時使用者數據
    protected $users = [];

    // 配置訊息
    protected $topic_weight = 4; // 主題權重
    protected $reply_weight = 1; // 回覆權重
    protected $pass_days = 7;    // 多少天內發表過內容
    protected $user_number = 6; // 取出來多少使用者

    // 緩存相關配置
    protected $cache_key = 'laravalforum_active_users';
    protected $cache_expire_in_minutes = 65;

    public function getActiveUsers()
    {
        // 嘗試從緩存中取出 cache_key 對應的數據。如果能取到，便直接返回數據
        // 否則使用匿名函數中的程式碼來取出活躍使用者數據，返回的同時做緩存
        return Cache::remember($this->cache_key, $this->cache_expire_in_minutes, function(){
            return $this->calculateActiveUsers();
        });
    }

    public function calculateAndCacheActiveUsers()
    {
        // 取得活躍使用者列表
        $active_users = $this->calculateActiveUsers();
        // 並加以緩存
        $this->cacheActiveUsers($active_users);
    }

    private function calculateActiveUsers()
    {
        $this->calculateTopicScore();
        $this->calculateReplyScore();

        // 陣列按照得分排序
        $users = array_sort($this->users, function ($user) {
            return $user['score'];
        });

        // 我們需要的是倒序，高分在前面，第二個參數為保持陣列的 KEY 不變
        $users = array_reverse($users, true);

        // 只獲取我們想要的數量
        $users = array_slice($users, 0, $this->user_number, true);

        // 新建一個空集合
        $active_users = collect();

        foreach ($users as $user_id => $user) {
            // 是否可以找到使用者
            $user = $this->find($user_id);

            // 如果資料庫裡有該使用者的話
            if ($user) {
                // 將此使用者放入集合的尾端
                $active_users->push($user);
            }
        }

        // 返回數據
        return $active_users;
    }

    private function calculateTopicScore()
    {
        // 從主題數據表裡取出限定時間範圍（$pass_days）內，有發表過主題的使用者
        // 並且同時取出使用者此段時間內發表主題的數量
        $topic_users = Topic::query()->select(DB::raw('user_id, count(*) as topic_count'))
                                     ->where('created_at', '>=', Carbon::now()->subDays($this->pass_days))
                                     ->groupBy('user_id')
                                     ->get();
        // 根據主題數量計算得分
        foreach ($topic_users as $value) {
            $this->users[$value->user_id]['score'] = $value->topic_count * $this->topic_weight;
        }
    }

    private function calculateReplyScore()
    {
        // 從回覆數據表裡取出限定時間範圍（$pass_days）內，有發表過回覆的使用者
        // 並且同時取出使用者此段時間內發佈回覆的數量
        $reply_users = Reply::query()->select(DB::raw('user_id, count(*) as reply_count'))
                                     ->where('created_at', '>=', Carbon::now()->subDays($this->pass_days))
                                     ->groupBy('user_id')
                                     ->get();
        // 根據回覆數量計算得分
        foreach ($reply_users as $value) {
            $reply_score = $value->reply_count * $this->reply_weight;
            if (isset($this->users[$value->user_id])) {
                $this->users[$value->user_id]['score'] += $reply_score;
            } else {
                $this->users[$value->user_id]['score'] = $reply_score;
            }
        }
    }

    private function cacheActiveUsers($active_users)
    {
        // 將數據放入緩存中
        Cache::put($this->cache_key, $active_users, $this->cache_expire_in_minutes);
    }
}
