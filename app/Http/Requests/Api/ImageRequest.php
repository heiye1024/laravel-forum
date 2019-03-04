<?php

namespace App\Http\Requests\Api;

use Dingo\Api\Http\FormRequest;

class ImageRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'type' => 'required|string|in:avatar,topic',
        ];

        if ($this->type == 'avatar') {
            $rules['image'] = 'required|mimes:jpeg,bmp,png,gif|dimensions:min_width=200,min_height=200';
        } else {
            $rules['image'] = 'required|mimes:jpeg,bmp,png,gif';
        }

        return $rules;
    }

    public function messages()
    {
      return [
          'image.dimensions' => '圖片的解析度不夠，寬和高需要200px以上',
      ];
    }
}
