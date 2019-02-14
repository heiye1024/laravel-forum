<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Topic;

class TopicPolicy extends Policy
{
    // 只有當主題的作者ID 等於 目前登入的使用者ID，才可以進行更新主題
    public function update(User $user, Topic $topic)
    {
        return $topic->user_id == $user->id;
    }

    public function destroy(User $user, Topic $topic)
    {
        return true;
    }
}
