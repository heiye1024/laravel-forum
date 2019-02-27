<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Overtrue\EasySms\EasySms;
use App\Http\Requests\Api\VerificationCodeRequest;

class VerificationCodesController extends Controller
{
    public function store(VerificationCodeRequest $request, EasySms $easySms)
    {
        $phone = $request->phone;

        if (!app()->environment('production')) {
            $code = '1234';
        } else {
            // 產生4位隨機數，左側補0
            $code = str_pad(random_int(1, 9999), 4, 0, STR_PAD_LEFT);

            // 用 easySms 發送簡訊到使用者手機
            try {
                $result = $easySms->send($phone, [
                    'content'  =>  "【Laravel-Forum】您的驗證碼是{$code}。"
                ]);
            } catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $exception) {
                $message = $exception->getException('yunpian')->getMessage();
                return $this->response->errorInternal($message ?: '簡訊發送異常');
            }
        }

        // 發送成功後，產生一個key
        $key = 'verificationCode_'.str_random(15);
        $expiredAt = now()->addMinutes(10);

        // 緩存驗證碼，10分鐘過期(在緩存中儲存這個 key 對應的手機和驗證碼)
        \Cache::put($key, ['phone' => $phone, 'code' => $code], $expiredAt);

        // 將 key 以及過期時間返回給Client端
        return $this->response->array([
            'key' => $key,
            'expired_at' => $expiredAt->toDateTimeString(),
        ])->setStatusCode(201);
    }
}
