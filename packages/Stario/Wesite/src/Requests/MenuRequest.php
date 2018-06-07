<?php

namespace Stario\Wesite\Requests;

use Stario\Icenter\Requests\JsonRequest;

class MenuRequest extends JsonRequest
{

    public function rules()
    {
        return [
            'name' => 'required',
            'url' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => '菜单名称未填写',
            'url.required' => '链接地址未填写',
        ];
    }
}
