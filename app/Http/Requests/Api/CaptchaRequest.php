<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class CaptchaRequest extends FormRequest
{
    public function authorize()
    {
        return false;
    }

    public function rules()
    {
        // 正規表示式：中國手機號碼
        return [
            'phone' => 'required|regex:/^1[34578]\d{9}$/|unique:users',
        ];
    }
}
