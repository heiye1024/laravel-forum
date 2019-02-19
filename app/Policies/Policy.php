<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class Policy
{
    use HandlesAuthorization;


    public function before($user, $ability)
	{
	    // 如果使用者擁有管理內容的權限，就授權通過
        if ($user->can('manage_contents')) {
            return true;
        }
	}
}
