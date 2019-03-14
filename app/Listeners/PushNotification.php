<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\DatabaseNotification;
use JPush\Client;

class PushNotification implements ShouldQueue
{
    protected $client;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    // 當通知存入資料庫後，監聽eloquent.created: Illuminate\Notifications\DatabaseNotification這個事件，
    // 如果使用者已經有了Jpush的registration_id，則使用Jpush SDK將消息內容推送到使用者的APP中
    // 另外，使用 strip_tags 去除 notification 數據中的 HTML 標籤
    public function handle(DatabaseNotification $notification)
    {
        // 本地環境默認不推送
        if (app()->environment('local')) {
            return;
        }

        $user = $notification->notifiable;

        // 沒有 registration_id 的不推送
        if (!$user->registration_id) {
            return;
        }

        // 推播消息
        $this->client->push()
            ->setPlatform('all')
            ->addRegistrationId($user->registration_id)
            ->setNotificationAlert(strip_tags($notification->data['reply_content']))
            ->send();
    }
}
