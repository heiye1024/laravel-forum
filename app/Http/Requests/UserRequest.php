<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|between:3,25|regex:/^[A-Za-z0-9\-\_]+$/|unique:users,name,' . Auth::id(),
            'email' => 'required|email',
            'introduction' => 'max:80',
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => '使用者名稱已經被使用，請重新填寫',
            'name.regex' => '使用者名稱只支援英文、數字、橫槓、下底線',
            'name.between' => '使用者名稱必須介於 3- 25字元之間',
            'name.required' => '使用者名稱不能為空',
        ];
    }
}
