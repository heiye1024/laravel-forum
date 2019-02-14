<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Models\Topic;
use App\Handlers\SlugTranslateHandler;

// ShouldQueue 表示應該將該job添加到後台的job queue中，而不是同步執行
// 使用Queue可以異步執行消耗時間的任務，降低請求回應時間
// 使用場景：比較耗時且不需要即時同步返回結果的操作
class TranslateSlug implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $topic;

    // 初始化一些 handle() 方法需要用到的參數
    public function __construct(Topic $topic)
    {
        // Queue job 建構子中接收了 Eloquent Model，將會只序列化 Model 的 ID
        $this->topic = $topic;
    }

    // 用來真正處理那些耗時的操作
    public function handle()
    {
        // 請求百度 API 進行翻譯
        $slug = app(SlugTranslateHandler::class)->translate($this->topic->title);

        // job中避免使用create(), update(), save()等操作，否則會陷入deadlock
        // 在這種情況下，直接使用DB Class對資料庫進行操作
        \DB::table('topics')->where('id', $this->topic->id)->update(['slug' => $slug]);
    }
}
