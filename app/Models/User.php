<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Auth;
use Spatie\Permission\Traits\HasRoles;
// 用於檢查已經認證使用者的Token和使用範圍
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements JWTSubject
{
    use Traits\LastActivedAtHelper;
    use Traits\ActiveUserHelper;
    use HasRoles;
    use MustVerifyEmailTrait;
    use HasApiTokens;

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
        'name', 'phone', 'email', 'password', 'introduction', 'avatar',
        'weixin_openid', 'weixin_unionid', 'registration_id'
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

    public function markAsRead()
    {
        $this->notification_count = 0;
        $this->save();
        $this->unreadNotifications->markAsRead();
    }

    public function setPasswordAttribute($value)
    {
        // 如果值的長度等於60，就認為是已經做過加密的情況
        if (strlen($value) != 60) {

            // 不等於 60，做密碼加密處理
            $value = bcrypt($value);
        }

        $this->attributes['password'] = $value;
    }

    public function setAvatarAttribute($path)
    {
        // 如果不是`http`開頭，那就是從後台上傳的，需要補全URL
        if ( ! starts_with($path, 'http')) {

            // 拼接完整的URL
            $path = config('app.url') . "/uploads/images/avatars/$path";
        }

        $this->attributes['avatar'] = $path;
    }

    // 返回 User 的 ID
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    // 需要額外在 JWT 中增加的自定義內容，返回空陣列
    public function getJWTCustomClaims()
    {
        return [];
    }

    // 默認情況下，Passport會通過使用者的電子郵件找使用者，要支援手機登入，我們可以在使用者模型中定義findForPassport方法
    // Passport會先檢查使用者模型是否存在findForPassport方法，如果存在就通過findForPassport找使用者，而不是使用默認的電子郵件
    public function findForPassport($username)
    {
        filter_var($username, FILTER_VALIDATE_EMAIL) ?
            $credentials['email'] = $username :
            $credentials['phone'] = $username;

        return self::where($credentials)->first();
    }
}
