<?php

namespace Stario\Wesite\Requests;

use Stario\Icenter\Requests\JsonRequest;

class CategoryRequest extends JsonRequest
{

    public function rules()
    {
        return [
            'name' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => '分类名称未填写',
        ];
    }
}
