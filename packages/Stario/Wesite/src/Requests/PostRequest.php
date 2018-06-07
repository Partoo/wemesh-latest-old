<?php

namespace Stario\Wesite\Requests;

use Stario\Icenter\Requests\JsonRequest;

class PostRequest extends JsonRequest
{

    public function rules()
    {
        return [
            'title' => 'required',
            'content' => 'required',
            'categoryId' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'title.required' => '分类名称未填写',
            'content.required' => '文章内容未填写',
            'categoryId.required' => '未指定文章分类',
        ];
    }
}
