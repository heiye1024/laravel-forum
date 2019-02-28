<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserRequest;

class UsersController extends Controller
{
    public function store(UserRequest $request)
    {
        $verifyData = \Cache::get($request->verification_key);

        // 驗證碼過期或verification_key錯誤時，返回錯誤訊息
        if (!$verifyData) {
            return $this->response->error('驗證碼已失效', 422);
        }

        // 用 hash_equals 比對驗證碼和緩存是否一樣
        if (!hash_equals($verifyData['code'], $request->verification_code)) {
            // 返回401(沒有進行認證或者認證非法)
            return $this->response->errorUnauthorized('驗證碼錯誤');
        }

        $user = User::create([
            'name' => $request->name,
            'phone' => $verifyData['phone'],
            'password' => bcrypt($request->password),
        ]);

        // 清除驗證碼緩存
        \Cache::forget($request->verification_key);

        // 狀態碼為201，對建立新資源的POST操作進行回應。應該帶著指向新資源地址的 Location Header
        return $this->response->created();
    }
}
