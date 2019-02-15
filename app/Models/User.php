<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Auth;

class User extends Authenticatable implements MustVerifyEmailContract
{
    use MustVerifyEmailTrait;

    use Notifiable {
        notify as protected laravelNotify;
    }

    public function notify($instance)
    {
        // 如果要通知的人是目前使用者，就不必通知
        if ($this->id == Auth::id()) {
            return;
        }

        // 只有資料庫類型通知才需提醒，直接發送 Email 或者其他的都 Pass
        if (method_exists($instance, 'toDatabase')) {
            $this->increment('notification_count');
        }

        $this->laravelNotify($instance);
    }

    protected $fillable = [
        'name', 'email', 'password', 'introduction', 'avatar'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    // 一個使用者會有很多個主題
    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    // 判斷作者ID是否是目前登入的使用者ID
    public function isAuthorOf($model)
    {
        return $this->id == $model->user_id;
    }

    // 一個使用者可以擁有許多回覆
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }
}
