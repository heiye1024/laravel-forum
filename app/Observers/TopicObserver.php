<?php

namespace App\Observers;

use App\Models\Topic;
use App\Jobs\TranslateSlug;
// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class TopicObserver
{
    public function saving(Topic $topic)
    {
        // XSS 過濾
        $topic->body = clean($topic->body, 'user_topic_body');

        // 產生主題摘要
        $topic->excerpt = make_excerpt($topic->body);
    }

    // 在saved() 方法對應 Eloquent 的 saved 事件，此事件發生在建立和編輯時、資料寫入資料庫以後
    // 在saved() 方法中使用，確保我們在分發任務時，$topic->id 永遠有值
    // 如果直接寫在saving()，當在saving中分派任務，任務運行時通過資料ID尋找數據時可能會報錯
    // 因為saving的時候，資料還沒寫到資料庫，$topic->id 為 null
    public function saved(Topic $topic)
    {
        // 如果slug沒有內容，就使用翻譯器對title進行翻譯
        if ( ! $topic->slug) {
            // 推送任務到 queue
            dispatch(new TranslateSlug($topic));
        }
    }
}
