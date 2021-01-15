<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class RefreshTokenMiddleware extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     * @param $request
     * @param Closure $next
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|mixed
     * @throws JWTException
     */
    public function handle($request, Closure $next)
    {
        //检查请求token是否存在，没有则抛出异常
        $this->checkForToken($request);
        try {

            if ($this->auth->parseToken()->authenticate())
                return $next($request);

            throw new UnauthorizedHttpException('jwt-auth','未登录');

        }catch (TokenExpiredException $exception){
            //捕获token过期异常，刷新token并添加到响应头
            $newToken=$this->refreshToken();
            return $this->setAuthenticationHeader($next($request),$newToken);
        }
    }


    public function refreshToken(){
        try {
            //刷新token
            $newToken=$this->auth->refresh();
            //使用一次性登录保证此次请求成功
            Auth::guard('api')->onceUsingId(
                $this->auth->manager()
                    ->getPayloadFactory()
                    ->buildClaimsCollection()
                    ->toPlainArray()['sub']
            );


        }catch (JWTException $exception){
            //再次捕获异常（refresh 过期）,用户无法刷新出新token,重新登录
            throw new UnauthorizedHttpException('jwt-auth',$exception->getMessage());
        }

        return $newToken;
    }
}
