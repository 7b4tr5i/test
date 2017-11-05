<?php

namespace app\controller\middleware;

use Closure;
use app\model\Token;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CheckToken
{

    public function handle($request, Closure $next){
        $requestParams = $request->query();
        if(!array_key_exists('token', $requestParams))
            throw new AccessDeniedHttpException('Token is empty');

        if((Token::where('token', $requestParams['token'])->first()) == NULL)
            throw new AccessDeniedHttpException('Invalid token');

        return $next($request);
    }

}