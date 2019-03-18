<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Carbon\Carbon;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
		\App\Models\Reply::class => \App\Policies\ReplyPolicy::class,
		\App\Models\Topic::class => \App\Policies\TopicPolicy::class,
        'App\Model' => 'App\Policies\ModelPolicy',
        \App\Models\User::class  => \App\Policies\UserPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();


        // 我們註冊了路由，同時通過Passport的tokenExpireIn和refreshTokensExpireIn定義了訪問Token的過期時間，
        // 否則訪問Token是永久有效的。這裡我們定義access_token 15天內有效，refresh_token 30天內有效

        // Passport 的路由
        Passport::routes();
        // access_token 過期時間
        Passport::tokensExpireIn(Carbon::now()->addDays(15));
        // refreshTokens 過期時間
        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));


        \Horizon::auth(function ($request) {
            // 是否是站長
            return \Auth::user()->hasRole('Founder');
        });
    }
}
