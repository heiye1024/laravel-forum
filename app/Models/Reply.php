<?php

namespace App\Models;

class Reply extends Model
{
    protected $fillable = ['content'];

    // 一條回覆屬於一個主題
    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    // 一條回覆屬於一個作者
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
