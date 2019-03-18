<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Topic;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Traits\ActingJWTUser;

class TopicApiTest extends TestCase
{
    use ActingJWTUser;

    protected $user;

    // setUp方法會在測試開始之前執行，我們先建立一個使用者，測試會以該使用者的身份進行測試
    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    // 一個測試使用者，測試發布主題，使用$this->json可以方便的模擬各種請求
    // 使用JWTActingAs方法，登入一個使用者，最後得到回應$reponse，通過assertStatus斷言回應結果為201，通過assertJsonFragment斷言回應結果包含assertData數據
    public function testStoreTopic()
    {
        $data = ['category_id' => 1, 'body' => 'test body', 'title' => 'test title'];

        $response = $this->JWTActingAs($this->user)
            ->json('POST', '/api/topics', $data);

        $assertData = [
            'category_id' => 1,
            'user_id' => $this->user->id,
            'title' => 'test title',
            'body' => clean('test body', 'user_topic_body'),
        ];

        $response->assertStatus(201)
            ->assertJsonFragment($assertData);
    }

    // 要修改主題，首先需要替使用者建立一個主題，所以增加了makeTopic()，為目前測試的使用者產生一個主題
    // 準備好要修改的主題數據 $editData，調用修改主題的API，修改剛才建立的主題，最後斷言回應狀態碼為200，以及結果中包含$assertData
    public function testUpdateTopic()
    {
        $topic = $this->makeTopic();

        $editData = ['category_id' => 2, 'body' => 'edit body', 'title' => 'edit title'];

        $response = $this->JWTActingAs($this->user)
            ->json('PATCH', '/api/topics/'.$topic->id, $editData);

        $assertData= [
            'category_id' => 2,
            'user_id' => $this->user->id,
            'title' => 'edit title',
            'body' => clean('edit body', 'user_topic_body'),
        ];

        $response->assertStatus(200)
            ->assertJsonFragment($assertData);
    }

    protected function makeTopic()
    {
        return factory(Topic::class)->create([
            'user_id' => $this->user->id,
            'category_id' => 1,
        ]);
    }

    // 增加兩個測試使用者 testShowTopic 和 testIndexTopic，分別測試主題詳情和主題列表
    // 這兩個 API 不需要使用者登入即可訪問，所以不需要傳入 Token

    // testShowTopic 先建立一個主題，然後訪問主題詳情API，斷言回應狀態碼 200 以及回應數據與新建的主題數據一致
    public function testShowTopic()
    {
        $topic = $this->makeTopic();
        $response = $this->json('GET', '/api/topics/'.$topic->id);

        $assertData= [
            'category_id' => $topic->category_id,
            'user_id' => $topic->user_id,
            'title' => $topic->title,
            'body' => $topic->body,
        ];

        $response->assertStatus(200)
            ->assertJsonFragment($assertData);
    }

    // 直接訪問主題列表API，斷言回應狀態碼200，斷言回應數據結構中有data和meta
    public function testIndexTopic()
    {
        $response = $this->json('GET', '/api/topics');

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'meta']);
    }

    // 首先透過 makeTopic 新建一個主題，然後偷過DELETE方法調用刪除主題API，將主題刪除，斷言回應狀態碼為204
    // 接著請求主題詳情API，斷言狀態碼為404，因為該主題已經被刪除了，所以會得到404
    public function testDeleteTopic()
    {
        $topic = $this->makeTopic();
        $response = $this->JWTActingAs($this->user)
            ->json('DELETE', '/api/topics/'.$topic->id);
        $response->assertStatus(204);

        $response = $this->json('GET', '/api/topics/'.$topic->id);
        $response->assertStatus(404);
    }

}
