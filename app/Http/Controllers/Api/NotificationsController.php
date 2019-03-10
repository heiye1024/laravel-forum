<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Transformers\NotificationTransformer;

class NotificationsController extends Controller
{
    public function index()
    {
        // 使用者模型的notifications方法是Laravel消息通知系統為我們提供的方法，按照通知建立時間倒敘排序
        $notifications = $this->user->notifications()->paginate(20);

        return $this->response->paginator($notifications, new NotificationTransformer());
    }
}
