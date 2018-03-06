<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/12/15
 * Time: 11:25
 */

namespace app\lib\exception;


class MessageException extends BaseException
{
    public $code = 404;
    public $msg = '验证码发送失败';
    public $errorCode = 70000;
}