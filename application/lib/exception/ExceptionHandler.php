<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/11/28
 * Time: 10:02
 */

namespace app\lib\exception;


use Exception;
use think\exception\Handle;
use think\Log;
use think\Request;

class ExceptionHandler extends Handle
{
    private $code;
    private $msg;
    private $errorCode;

    /**
     * 重写异常抛出类，自定义异常，需要配置抛出异常的handler文件
     * @param Exception $e
     * @return \think\Response|\think\response\Json
     */
    public function render(Exception $e)
    {
        if ($e instanceof BaseException) {
            /**
             * 如果是自定义异常，则控制http状态码，不需要记录日志
             * 因为这些通常是因为客户端传递参数错误或者是用户请求造成的异常
             * 不应当记录日志
             */
            $this->code = $e->code;
            $this->msg = $e->msg;
            $this->errorCode = $e->errorCode;
        } else {
            /**
             * 服务器异常则应该记录日志，是否打开了调试模式
             */
            if (config('app_debug')) {
                return parent::render($e);
            }

            $this->code = 500;
            $this->msg = 'we made mistakes.';
            $this->errCode = 99999;
            $this->errorLog($e);

        }
        $request = Request::instance();
        $result = [
            'msg' => $this->msg,
            'errCode' => $this->errorCode,
            'request_url' => $request->url()
        ];
        return json($result, $this->code);
    }

    private function errorLog(Exception $e)
    {
        Log::init([
            'type' => 'file',
            'path' => LOG_PATH,
            'level' => ['error']
        ]);

        Log::record($e->getMessage(), 'error');
    }
}