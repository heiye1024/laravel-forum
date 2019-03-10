<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api',
    'middleware' => ['serializer:array', 'bindings']
], function($api) {

    $api->group([
        'middleware' => 'api.throttle',
        'limit' => config('api.rate_limits.sign.limit'),
        'expires' => config('api.rate_limits.sign.expires'),
    ], function($api) {
        // 簡訊驗證碼
        $api->post('verificationCodes', 'VerificationCodesController@store')
            ->name('api.verificationCodes.store');
        // 使用者註冊
        $api->post('users', 'UsersController@store')
            ->name('api.users.store');
        // 圖片驗證碼
        $api->post('captchas', 'CaptchasController@store')
            ->name('api.captchas.store');
        // 第三方登入
        $api->post('socials/{social_type}/authorizations', 'AuthorizationsController@socialStore')
            ->name('api.socials.authorizations.store');
        // 使用者登入
        $api->post('authorizations', 'AuthorizationsController@store')
            ->name('api.authorizations.store');
        // 刷新 token
        $api->put('authorizations/current', 'AuthorizationsController@update')
            ->name('api.authorizations.update');
        // 刪除 token
        $api->delete('authorizations/current', 'AuthorizationsController@destroy')
            ->name('api.authorizations.destroy');
    });

    $api->group([
        'middleware' => 'api.throttle',
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], function ($api) {
        // 訪客可以訪問的API
        // 分類列表
        $api->get('categories', 'CategoriesController@index')
            ->name('api.categories.index');
        // 主題列表
        $api->get('topics', 'TopicsController@index')
            ->name('api.topics.index');
        // 主題詳細內容
        $api->get('topics/{topic}', 'TopicsController@show')
            ->name('api.topics.show');
        // 某個使用者發佈的主題
        $api->get('users/{user}/topics', 'TopicsController@userIndex')
            ->name('api.users.topics.index');
        // 主題回覆列表
        $api->get('topics/{topic}/replies', 'RepliesController@index')
            ->name('api.topics.replies.index');
        // 某個使用者的回覆列表
        $api->get('users/{user}/replies', 'RepliesController@userIndex')
            ->name('api.users.replies.index');

        // 需要token驗證的API
        $api->group(['middleware' => 'api.auth'], function($api) {
            // 當前登入使用者訊息
            $api->get('user', 'UsersController@me')
                ->name('api.user.show');
            // 編輯登入使用者訊息(put替換某個資源，需提供完整的資源訊息；這邊使用patch，是部分修改資源，提供部分資源訊息)
            $api->patch('user', 'UsersController@update')
                ->name('api.user.update');
            // 圖片資源
            $api->post('images', 'ImagesController@store')
                ->name('api.images.store');
            // 發佈主題
            $api->post('topics', 'TopicsController@store')
                ->name('api.topics.store');
            // 修改主題(使用patch，Postman需使用x-www-form-urlencoded)
            $api->patch('topics/{topic}', 'TopicsController@update')
                ->name('api.topics.update');
            // 刪除主題
            $api->delete('topics/{topic}', 'TopicsController@destroy')
                ->name('api.topics.destroy');
            // 發佈回覆(回覆一定屬於某個主題，所以設計成topics/{topic}/replies，為某個主題增加回覆，這樣會讓彼此的關係更加直覺)
            $api->post('topics/{topic}/replies', 'RepliesController@store')
                ->name('api.topics.replies.store');
            // 刪除回覆
            $api->delete('topics/{topic}/replies/{reply}', 'RepliesController@destroy')
                ->name('api.topics.replies.destroy');
            // 消息通知列表
            $api->get('user/notifications', 'NotificationsController@index')
                ->name('api.user.notifications.index');
            // 未讀消息統計(stats是statistics的縮寫)
            $api->get('user/notifications/stats', 'NotificationsController@stats')
                ->name('api.user.notifications.stats');
        });
    });
});
