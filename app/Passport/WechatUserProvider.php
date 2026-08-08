<?php

namespace App\Passport;

use App\Models\User;
use DateInterval;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\Bridge\User as PassportUser;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Grant\AbstractGrant;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use League\OAuth2\Server\RequestAccessTokenEvent;
use League\OAuth2\Server\RequestEvent;
use League\OAuth2\Server\RequestRefreshTokenEvent;
use League\OAuth2\Server\ResponseTypes\ResponseTypeInterface;
use Overtrue\LaravelSocialite\Socialite;
use Overtrue\Socialite\Exceptions\AuthorizeFailedException;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

class WechatUserProvider extends AbstractGrant
{
    public function __construct(
        RefreshTokenRepositoryInterface $refreshTokenRepository,
    ) {
        $this->setRefreshTokenRepository($refreshTokenRepository);
        $this->refreshTokenTTL = new DateInterval('P1M');
    }

    public function respondToAccessTokenRequest(
        ServerRequestInterface $request,
        ResponseTypeInterface $responseType,
        DateInterval $accessTokenTTL,
    ): ResponseTypeInterface {
        $client = $this->validateClient($request);
        $scopes = $this->validateScopes($this->getRequestParameter('scope', $request));

        $this->validate($request);
        $user = $this->retrieve($request);

        if ($user instanceof Authenticatable) {
            $user = new PassportUser((string) $user->getAuthIdentifier());
        }

        if (! $user instanceof UserEntityInterface) {
            $this->getEmitter()->emit(new RequestEvent(
                RequestEvent::USER_AUTHENTICATION_FAILED,
                $request,
            ));

            throw OAuthServerException::invalidCredentials();
        }

        $finalizedScopes = $this->scopeRepository->finalizeScopes(
            $scopes,
            $this->getIdentifier(),
            $client,
            $user->getIdentifier(),
        );

        $accessToken = $this->issueAccessToken(
            $accessTokenTTL,
            $client,
            $user->getIdentifier(),
            $finalizedScopes,
        );

        $this->getEmitter()->emit(new RequestAccessTokenEvent(
            RequestEvent::ACCESS_TOKEN_ISSUED,
            $request,
            $accessToken,
        ));
        $responseType->setAccessToken($accessToken);

        $refreshToken = $this->issueRefreshToken($accessToken);

        if ($refreshToken !== null) {
            $this->getEmitter()->emit(new RequestRefreshTokenEvent(
                RequestEvent::REFRESH_TOKEN_ISSUED,
                $request,
                $refreshToken,
            ));
            $responseType->setRefreshToken($refreshToken);
        }

        return $responseType;
    }

    public function getIdentifier(): string
    {
        return 'wechat-social';
    }

    public function validate(ServerRequestInterface $request): void
    {
        $validator = Validator::make((array) $request->getParsedBody(), [
            'code' => ['required_without:access_token', 'string'],
            'access_token' => ['required_without:code', 'string'],
            'openid' => ['required_with:access_token', 'string'],
        ]);

        if ($validator->fails()) {
            $field = $validator->errors()->keys()[0];

            throw OAuthServerException::invalidRequest(
                $field,
                $validator->errors()->first($field),
            );
        }
    }

    public function retrieve(ServerRequestInterface $request): ?User
    {
        $inputs = (array) $request->getParsedBody();
        $driver = Socialite::create('wechat');

        try {
            if (! empty($inputs['code'])) {
                $oauthUser = $driver->userFromCode($inputs['code']);
            } else {
                $driver->withOpenid($inputs['openid']);
                $oauthUser = $driver->userFromToken($inputs['access_token']);
            }
        } catch (AuthorizeFailedException $exception) {
            throw OAuthServerException::invalidGrant($exception->getMessage());
        } catch (Throwable $exception) {
            report($exception);

            throw OAuthServerException::invalidGrant(
                'Unable to retrieve the WeChat user.',
            );
        }

        $openid = $oauthUser->getId();

        if (! $openid) {
            throw OAuthServerException::invalidGrant(
                'Unable to retrieve the WeChat user.',
            );
        }

        $rawUser = $oauthUser->getRaw();
        $unionid = $rawUser['unionid'] ?? null;
        $user = $unionid
            ? User::where('weixin_unionid', $unionid)->first()
            : User::where('weixin_openid', $openid)->first();

        if ($user) {
            return $user;
        }

        $attributes = [
            'name' => $oauthUser->getNickname() ?: 'wechat_'.$openid,
            'weixin_openid' => $openid,
            'weixin_unionid' => $unionid,
        ];

        if ($avatar = $oauthUser->getAvatar()) {
            $attributes['avatar'] = $avatar;
        }

        return User::create($attributes);
    }
}
