<?php
namespace Stario\Icenter\Passport;

use Stario\Iwrench\Passport\BaseProxy;

class AdminAuthProxy extends BaseProxy
{
    // private $admin;

    // public function __construct(Admin $admin)
    // {
    //     $this->admin = $admin;
    // }
    /**
     * 当前看是多余的，不过日后使用 multi-auth 就可以使用了
     *
     * @return array
     */
    public function params()
    {
        return [
            // 'provider' => 'api',
            'scope' => '',
        ];
    }
    /**
     * 用户注册、登录均可以使用
     *
     * @param [type] $request
     * @return json
     */
    public function makeToken($request)
    {
        return $this->make('password', $request);
    }
}
