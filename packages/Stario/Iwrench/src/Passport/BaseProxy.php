<?php
namespace Stario\Iwrench\Passport;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Laravel\Passport\Client as Passport;
use Stario\Iwrench\Reaction\Reaction;

/**
 * 基于 Laravel Passport，用于获取 token
 * 之所以抽象此类，原意利用 Passport 实现 Multi Auth 功能，即同时用于 patients 和 admins 的验证
 * 但考虑再三，当前方案为 admins 使用 Passport，patients 部分当前均为微网站，故全部基于微信验证
 */

abstract class BaseProxy
{
    /**
     * attemptLogin 实现方法如：
     *
     *public function attemptLogin($request) {
     *$user = $this->user->where('mobile', '=', $request['mobile'])->first();
     *if (!empty($user)) {
     *return $this->proxy('password', ['username' => $request['mobile'], 'password' => $request['password']]);
     *}
     *return StarJson::create(401);
     *}
     */
    // abstract public function attemptLogin($request);

    /**
     * params 实现方法如：
     *
     *public function params() {
     *return [
     *'client_id' => env('PASSWORD_CLIENT_ID'),
     *'client_secret' => env('PASSWORD_CLIENT_SECRET'),
     *'provider' => 'api',
     *'scope' => '',
     *];
     *}
     */
    abstract public function params();
    /**
     * register 用于注册新的用户
     *
     * @return void
     */
    // abstract public function register();

    public function make($grantType, array $request = [])
    {
        // 查看缓存，如果没有client_id和client_secret则从生成
        if (!Cache::has('client_id') || !Cache::has('client_secret')) {
            $client = Passport::query()->where('password_client', 1)->latest()->first();
            Cache::forever('client_id', $client->id);
            Cache::forever('client_secret', $client->secret);
        }

        $params = array_merge(
            $request,
            [
                'client_id' => Cache::get('client_id'),
                'client_secret' => Cache::get('client_secret'),
                'grant_type' => $grantType,
                'scope' => $this->params()['scope'],
                // provider有可能在使用passport多验证(issue #161)中用到，目前未用
                // 'provider' => $this->params()['provider'],
            ]
        );
        $http = new Client(['http_errors' => false]);
        $response = $http->request('POST', url('/oauth/token'), [
            'form_params' => $params,
        ]);
        // 为了照顾前端（见到401就用refresh_token刷新一次token），此处返回203
        if ($response->getStatusCode() == 401) {
            return Reaction::withRefreshTokenFailed(json_decode($response->getBody()->getContents())->message);
        } else if ($response->getStatusCode() != 200) {
            return Reaction::withBadRequest(json_decode($response->getBody()->getContents())->message);
        }

        $data = json_decode($response->getBody()->getContents());
        // 如果是client_credentials 类型，返回下面的内容
        if ($grantType == 'client_credentials') {
            return response([
                'token_type' => $data->token_type,
                'access_token' => $data->access_token,
            ]);
        }
        // 通常返回：
        return response()->json([
            'access_token' => $data->access_token,
            'refresh_token' => $data->refresh_token,
        ], 201);
        // ->cookie('star_token', $data->refresh_token, $data->expires_in, null, null, false, true);

    }
    /**
     * 刷新token
     * @param  $refreshToken
     * @return  json
     */
    public function attemptRefresh($refreshToken)
    {
        return $this->make('refresh_token', [
            'refresh_token' => $refreshToken,
        ], 201);
        // ->cookie('star_token', $data->refresh_token, $data->expires_in, null, null, false, true);

    }
}
