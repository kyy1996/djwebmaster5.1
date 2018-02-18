<?php

namespace app\common\exception;

use Exception;
use think\Container;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\Handle;
use think\exception\HttpException;
use think\exception\RouteNotFoundException;
use think\exception\ValidateException;

class ExceptionHandler extends Handle
{
    public function render(Exception $e)
    {
        $data = $this->collectExceptionData($e);
        //API错误，统一返回Json
        if (request()->module('api')) {
            $status = $this->getStatusCode($e);
            return json($data, $status);
        }

        // 其他错误交给系统处理
        return parent::render($e);
    }

    protected function getStatusCode(\Exception $e)
    {
        //默认服务器错误状态码
        $status = 500;

        // 客户端请求参数错误
        if ($e instanceof ValidateException) {
            $status = 400;
        }

        //资源未找到
        if ($e instanceof ModelNotFoundException
            || $e instanceof RouteNotFoundException
            || $e instanceof DataNotFoundException) {
            $status = 404;
        }

        //用户权限不足
        if ($e instanceof UnauthorizedException) {
            $status = 401;
        }

        //创建资源失败
        if (500 === $status && request()->action() == 'create') {
            $status = 422;
        }

        return $status;
    }

    protected function collectExceptionData(\Exception $exception)
    {
        // 收集异常数据
        if (Container::get('app')->isDebug()) {
            // 调试模式，获取详细的错误信息
            $data = [
                'name'    => get_class($exception),
                'file'    => $exception->getFile(),
                'line'    => $exception->getLine(),
                'message' => $this->getMessage($exception),
                'trace'   => $exception->getTrace(),
                'code'    => $this->getCode($exception),
                'source'  => $this->getSourceCode($exception),
                'data'    => $this->getExtendData($exception),
                'tables'  => [
                    'GET Data'              => $_GET,
                    'POST Data'             => $_POST,
                    'Files'                 => $_FILES,
                    'Cookies'               => $_COOKIE,
                    'Session'               => isset($_SESSION) ? $_SESSION : [],
                    'Server/Request Data'   => $_SERVER,
                    'Environment Variables' => $_ENV,
                ],
            ];
        } else {
            // 部署模式仅显示 Code 和 Message
            $data = [
                'code'    => $this->getCode($exception),
                'message' => $this->getMessage($exception),
            ];

            if (!Container::get('app')->config('show_error_msg')) {
                // 不显示详细错误信息
                $data['message'] = Container::get('app')->config('error_message');
            }
        }

        return $data;
    }

}
