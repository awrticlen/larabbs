<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Laravel\Passport\Http\Controllers\AccessTokenController;

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
}
