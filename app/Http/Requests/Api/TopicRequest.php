<?php

namespace App\Http\Requests\Api;

use Dingo\Api\Http\FormRequest;

class TopicRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string',
            'body' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ];
    }

    public function attributes()
    {
        return [
            'title' => '標題',
            'body' => '主題內容',
            'category_id' => '分類',
        ];
    }
}
