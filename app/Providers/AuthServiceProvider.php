<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

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

        //
    }
}
