<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class VerificationCodeRequest extends FormRequest
{
    public function authorize()
    {
        return false;
    }

    public function rules()
    {
        return [
            'captcha_key' => 'required|string',
            'captcha_code' => 'required|string',
        ];
    }

    public function attributes()
    {
        return [
            'captcha_key' => '圖片驗證碼 key',
            'captcha_code' => '圖片驗證碼',
        ];
    }
}
