<?php

namespace App\Http\Controllers\Api;

use App\Models\Image;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Transformers\UserTransformer;
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
        // 為了使用者註冊後，直接登入該使用者，所以需要返回一些數據
        return $this->response->item($user, new UserTransformer())
            ->setMeta([
                'access_token' => \Auth::guard('api')->fromUser($user),
                'token_type' => 'Bearer',
                'expires_in' => \Auth::guard('api')->factory()->getTTL() * 60
            ])
            ->setStatusCode(201);
    }

    public function update(UserRequest $request)
    {
        $user = $this->user();

        $attributes = $request->only(['name', 'email', 'introduction']);

        if ($request->avatar_image_id) {
            $image = Image::find($request->avatar_image_id);

            $attributes['avatar'] = $image->path;
        }
        $user->update($attributes);

        return $this->response->item($user, new UserTransformer());
    }

    // 這裡使用Dingo\Api\Routing\Helpers中的trait，它提供了user方法，讓我們可以取得當前登入的使用者，也就是token對應的使用者
    // $this->user()等同於\Auth::guard('api')->user()
    // 我們返回的是一個單一資源，所以使用$this->response->item，第一個參數是模型實例，第二個參數是transformer
    public function me()
    {
        return $this->response->item($this->user(), new UserTransformer());
    }
}
