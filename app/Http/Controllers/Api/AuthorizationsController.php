<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\Api\SocialAuthorizationRequest;
use App\Http\Requests\Api\AuthorizationRequest;

class AuthorizationsController extends Controller
{
    public function store(AuthorizationRequest $request)
    {
        $username = $request->username;

        filter_var($username, FILTER_VALIDATE_EMAIL) ?
            $credentials['email'] = $username :
            $credentials['phone'] = $username;

        $credentials['password'] = $request->password;

        if (!$token = \Auth::guard('api')->attempt($credentials)) {
            return $this->response->errorUnauthorized('使用者名稱或密碼錯誤');
        }

        return $this->respondWithToken($token)->setStatusCode(201);
    }

    public function socialStore($type, SocialAuthorizationRequest $request)
    {
        if (!in_array($type, ['weixin'])) {
            return $this->response->errorBadRequest();
        }

        $driver = \Socialite::driver($type);

        try {
            // Client只能提交授權碼(code)，不然就要提交access_token和openid
            if ($code = $request->code) {
                $response = $driver->getAccessTokenResponse($code);
                $token = array_get($response, 'access_token');
            } else {
                $token = $request->access_token;

                if ($type == 'weixin') {
                    $driver->setOpenId($request->openid);
                }
            }

            $oauthUser = $driver->userFromToken($token);
        } catch (\Exception $e) {
            return $this->response->errorUnauthorized('參數錯誤，未取得使用者訊息');
        }

        // 不論哪種方式，Server都會用到微信API，獲取授權使用者數據，進而確定數據的有效性。
        // 不能從Client直接抓取使用者訊息，提交openid或unionid到Server，直接進入資料庫
        switch ($type) {
            case 'weixin':
                $unionid = $oauthUser->offsetExists('unionid') ? $oauthUser->offsetGet('unionid') : null;

                //根據openid或unionid去資料庫查詢使用者是否已經存在，如果不存在，則建立新使用者
                if ($unionid) {
                    $user = User::where('weixin_unionid', $unionid)->first();
                } else {
                    $user = User::where('weixin_openid', $oauthUser->getId())->first();
                }

                // 沒有使用者，默認新建立一個使用者
                if (!$user) {
                    $user = User::create([
                        'name' => $oauthUser->getNickname(),
                        'avatar' => $oauthUser->getAvatar(),
                        'weixin_openid' => $oauthUser->getId(),
                        'weixin_unionid' => $unionid,
                    ]);
                }

                break;
        }

        // 由Server替使用者頒發授權頻證
        // 第三方登入獲取 user 之後，我們可以使用 fromUser 方法為某一個使用者模型產生 token
        $token = Auth::guard('api')->fromUser($user);
        return $this->respondWithToken($token)->setStatusCode(201);
    }

    // 刷新 token
    public function update()
    {
        $token = Auth::guard('api')->refresh();
        return $this->respondWithToken($token);
    }

    // 刪除 token
    public function destroy()
    {
        Auth::guard('api')->logout();
        return $this->response->noContent();
    }

    // 登入和第三方登入都應該一樣，所以抽出來成一個函數
    protected function respondWithToken($token)
    {
        return $this->response->array([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => \Auth::guard('api')->factory()->getTTL() * 60
        ]);
    }
}
