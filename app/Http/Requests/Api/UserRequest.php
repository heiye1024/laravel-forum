<?php

namespace App\Http\Requests\Api;

use Dingo\Api\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        switch($this->method()) {
            case 'POST':
                return [
                    'name' => 'between:3,25|regex:/^[A-Za-z0-9\-\_]+$/|unique:users,name',
                    'password' => 'required|string|min:6',
                    'verification_key' => 'required|string',
                    'verification_code' => 'required|string',
                ];
                break;
            case 'PATCH':
                $userId = \Auth::guard('api')->id();
                return [
                    'name' => 'between:3,25|regex:/^[A-Za-z0-9\-\_]+$/|unique:users,name,' .$userId,
                    'email' => 'email',
                    'introduction' => 'max:80',
                    'avatar_image_id' => 'exists:images,id,type,avatar,user_id,'.$userId,
                ];
                break;
        }
    }

    public function attributes()
    {
        return [
            'verification_key' => '簡訊驗證碼 key',
            'verification_code' => '簡訊驗證碼',
            'introduction' => '個人簡介',
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => '使用者名稱已經被使用，請重新填寫',
            'name.regex' => '使用者名稱只支持英文、數字、橫槓和下底線',
            'name.between' => '使用者名稱必須介於 3 - 25 個字元之間',
            'name.required' => '使用者名稱不能為空',
        ];
    }
}
