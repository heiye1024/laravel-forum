<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Topic;

class TopicPolicy extends Policy
{
    // 只有當主題的作者ID 等於 目前登入的使用者ID，才可以進行更新主題
    public function update(User $user, Topic $topic)
    {
        return $user->isAuthorOf($topic);
    }

    // 允許作者刪除主題
    public function destroy(User $user, Topic $topic)
    {
        return $user->isAuthorOf($topic);
    }
}
