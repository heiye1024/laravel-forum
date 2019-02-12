<?php

namespace App\Models;

class Topic extends Model
{
    protected $fillable = [
        'title', 'body', 'category_id', 'excerpt', 'slug'
    ];

    // 一個主題屬於一個分類
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // 一個主題擁有一個作者
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeWithOrder($query, $order)
    {
        // 不同的排序，使用不同的資料查詢邏輯
        switch ($order) {
            case 'recent':
                $query->recent();
                break;

            default:
                $query->recentReplied();
                break;
        }

        // 預加載防止 N+1 問題
        return $query->with('user', 'category');
    }

    public function scopeRecentReplied($query)
    {
        // 當主題有新回覆時，我們將編寫邏輯來更新Topic Model的reply_count屬性，
        // 此時會自動觸發框架對數據模型 updated_at 的更新
        return $query->orderBy('updated_at', 'desc');
    }

    public function scopeRecent($query)
    {
        // 按照建立時間排序
        return $query->orderBy('created_at', 'desc');
    }
}
