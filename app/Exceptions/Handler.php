<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * [description]
     * @param  Exception  $exception
     * @return mixed|void
     * @throws Exception
     * @author: cuibo 2019/10/8 11:53
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * [description]
     * @param  \Illuminate\Http\Request  $request
     * @param  Exception  $exception
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response|null
     * @author: cuibo 2019/10/8 11:54
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof ValidationException) {
            $exception->status(200);
            return $this->convertValidationExceptionToResponse($exception, $request);
        }
        //检测是否登录 验证失败时 抛出的异常
        if ($exception instanceof AuthenticationException) {
            // $exception->status(200);
            return response()->json(['status' => 40001, 'msg' => 'no-login', 'redirect_url' => '/admin/login']);
            // return $this->convertValidationExceptionToResponse($exception, $request);
        }
        return parent::render($request, $exception);
    }

    public function unauthenticated($request, AuthenticationException $exception)
    {
        return $request->expectsJson()
            ? response()->json(['message' => $exception->getMessage()], 401)
            : redirect()->guest(route('admin.login'));
    }

    /**
     * 重写 修改json相应时的格式
     * @param  [type]              $request   [description]
     * @param ValidationException $exception [description]
     * @return \Illuminate\Http\Response | \Symfony\Component\HttpFoundation\Response
     * @author cuibo weiai525@outlook.com at 2018-05-03
     */
    protected function invalidJson($request, ValidationException $exception)
    {
        $message = $exception->getMessage();
        $errors = $exception->errors();
        if (count($errors) > 0) {
            $ls = Arr::first($errors);
            $message = $ls[0];
        }
        return response()->json([
            'status' => 30001,
            'msg' => $message,
            'errors' => $exception->errors(),
            'auto_msg' => true,
        ], $exception->status);
    }
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        Log::debug('validator_error:', $request->all());
        if ($e->response) {
            return $e->response;
        }
        return $request->expectsJson() || env('APP_ENV') != 'production'
            ? $this->invalidJson($request, $e)
            : $this->invalid($request, $e);
    }
}
