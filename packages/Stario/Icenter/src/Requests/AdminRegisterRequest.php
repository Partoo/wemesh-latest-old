<?php

namespace Stario\Icenter\Requests;

use Stario\Icenter\Requests\JsonRequest;

class AdminRegisterRequest extends JsonRequest
{

    public function rules()
    {
        return [
            'mobile' => 'required|unique:admins',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
            'authcode' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'mobile.required' => '手机号码未填写',
            'mobile.unique' => '该手机号码已经注册，请直接登录',
            'password.required' => '密码未填写',
            'password.confirmed' => '密码不匹配',
            'password_confirmed.required' => '需要确认一遍密码',
            'authcode.required' => '手机验证码未填写',
        ];
    }
}
