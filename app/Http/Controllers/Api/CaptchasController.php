<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Gregwar\Captcha\CaptchaBuilder;
use App\Http\Requests\Api\CaptchaRequest;

class CaptchasController extends Controller
{
    // 圖片驗證碼 API 流程
    // 1. 產生圖片驗證碼
    // 2. 產生隨機的key，將驗證碼存入緩存
    // 3. 返回隨機的key，以及驗證碼圖片

    // 使用CaptchaRequest要求使用者必須通過手機號碼調用圖片驗證碼API
    public function store(CaptchaRequest $request, CaptchaBuilder $captchaBuilder)
    {
        // 產生隨機的key
        $key = 'captcha-'.str_random(15);
        $phone = $request->phone;

        // 注入CaptchaBuilder，透過build()，建立驗證碼圖片
        $captcha = $captchaBuilder->build();
        $expiredAt = now()->addMinutes(2);
        // getPhrase()獲取驗證碼，跟手機號碼一起存入緩存
        \Cache::put($key, ['phone' => $phone, 'code' => $captcha->getPhrase()], $expiredAt);

        // 返回captcha_key，2分鐘過期時間以及inline()獲取base64圖片驗證碼
        $result = [
            'captcha_key' => $key,
            'expired_at' => $expiredAt->toDateTimeString(),
            'captcha_image_content' => $captcha->inline()
        ];

        return $this->response->array($result)->setStatusCode(201);
    }
}
