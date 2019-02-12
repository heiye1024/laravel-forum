<?php

namespace App\Models;

class Topic extends Model
{
    protected $fillable = [
        'title', 'body', 'user_id', 'category_id', 'reply_count',
        'view_count', 'last_reply_user_id', 'order', 'excerpt', 'slug'
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
}
