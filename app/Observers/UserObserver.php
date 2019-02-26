<?php

namespace App\Observers;

use App\Models\User;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class UserObserver
{
    public function saving(User $user)
    {
        // 這樣寫的擴充性較高，只有空的時候才指定默認頭像
        if (empty($user->avatar)) {
            $user->avatar = 'https://iocaffcdn.phphub.org/uploads/images/201710/30/1/TrJS40Ey5k.png';
        }
    }
}
