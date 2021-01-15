<?php


namespace App\Exceptions;

use Exception;
use Throwable;
use Illuminate\Http\Request;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class ExceptionReport
{
    public $exception;     //异常
    public $request;       //请求
    protected $report;     //响应

    function __construct(Request $request, Throwable $exception)
    {
        $this->request = $request;
        $this->exception = $exception;
    }

    //常见异常放在这里(自定义状态码和错误信息)
    public $doReport = [
        AuthenticationException::class       => ['status'=>401, 'msg'=>'未授权'],
        ModelNotFoundException::class        => ['status'=>404, 'msg'=>'该模型未找到'],
        AuthorizationException::class        => ['status'=>403, 'msg'=>'没有此权限'],
        UnauthorizedHttpException::class     => ['status'=>422, 'msg'=>'未登录或登录状态失效'],
        TokenInvalidException::class         => ['status'=>400, 'msg'=>'token不正确'],
        NotFoundHttpException::class         => ['status'=>404, 'msg'=>'没有找到该页面'],
        MethodNotAllowedHttpException::class => ['status'=>405, 'msg'=>'访问方式不正确'],
        QueryException::class                => ['status'=>401, 'msg'=>'参数错误'],
        ValidationException::class           => [],
    ];

    /**
     * 登记
     * @param $className
     * @param callable $callback
     */
    public function register($className, callable $callback)
    {
        $this->doReport[$className] = $callback;
    }

    /**
     * 拦截
     * @param Throwable $e
     * @return static
     */
    public static function make(Throwable $e)
    {
        return new static(\request(), $e);
    }

    /**
     * 异常判断
     * @return bool
     */
    public function shouldReturn()
    {
        //json或者ajax请求时才有效
        /* if (! ($this->request->wantsJson() || $this->request->ajax())){
              return false;
         } */
        foreach (array_keys($this->doReport) as $report) {
            if ($this->exception instanceof $report) {
                $this->report = $report;
                return true;
            }
        }
        return false;
    }

    /**
     * 返回错误信息
     * @return \Illuminate\Http\JsonResponse
     */
    public function report()
    {
        if ($this->exception instanceof ValidationException) {
            return api(422, $this->exception->errors());
        }

        $errorData = $this->doReport[$this->report];
        return api($errorData['status'], $errorData['msg']);
    }

    /**
     * 线上环境,未知错误，500
     * @return \Illuminate\Http\JsonResponse
     */
    public function prodReport()
    {
        return api(500, '服务器错误');
    }

}
