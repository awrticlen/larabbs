<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\WeappAuthorizationRequest;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;


class AuthorizationsController extends AccessTokenController
{
    public function store(
        ServerRequestInterface $request,
        ResponseInterface $response
    ) {
        return $this->issueToken($request, $response)
            ->setStatusCode(201);
    }
    public function update(
        ServerRequestInterface $request,
        ResponseInterface $response
    ) {
        return $this->issueToken($request, $response);
    }
    public function destroy()
    {
        if (auth('api')->check()) {
            auth('api')->user()->token()->revoke();
            return response(null, 204);
        } else {
            throw new AuthenticationException('The token is invalid.');
        }
    }
    public function weappStore(WeappAuthorizationRequest $request)
    {
        $code = $request->code;

        // 根据 code 获取微信 openid 和 session_key
        $miniApp = app('easywechat.mini_app');
        $utils = $miniApp->getUtils();
        $data = $utils->codeToSession($code);

        // 如果结果错误，说明 code 已过期或不正确，返回 401 错误
        if (isset($data['errcode'])) {
            throw new AuthenticationException('code 不正确');
        }

        // 找到 openid 对应的用户
        $user = User::where('weapp_openid', $data['openid'])->first();

        $attributes['weixin_session_key'] = $data['session_key'];

        // 未找到对应用户则需要提交用户名密码进行用户绑定
        if (!$user) {
            // 如果未提交用户名密码，403 错误提示
            if (!$request->filled('username') || !$request->filled('password')) {
                throw new AuthenticationException('用户名和密码不能为空');
            }

            $username = $request->username;

            // 用户名可以是邮箱或电话
            $user = filter_var($username, FILTER_VALIDATE_EMAIL)
                ? User::where('email', $username)->first()
                : User::where('phone', $username)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                throw new AuthenticationException('用户名或密码错误');
            }

            $attributes['weapp_openid'] = $data['openid'];
        }

        // 更新用户数据
        $user->update($attributes);

        // 为用户创建 Passport access token
        return $this->tokenResponse($user);
    }

    public function weappRefresh()
    {
        $user = auth('api')->user();
        $currentToken = $user?->token();

        if (!$user) {
            throw new AuthenticationException('The token is invalid.');
        }

        $response = $this->tokenResponse($user, 200);
        $currentToken?->revoke();

        return $response;
    }

    private function tokenResponse(User $user, int $status = 201)
    {
        $token = $user->createToken('weapp');

        return response()->json([
            'token_type' => $token->tokenType,
            'access_token' => $token->accessToken,
            'expires_in' => $token->expiresIn,
        ], $status);
    }
}