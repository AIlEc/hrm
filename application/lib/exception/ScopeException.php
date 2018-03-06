<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/12/11
 * Time: 15:49
 */

namespace app\lib\exception;


class ScopeException extends BaseException
{
    public $code = 401;
    public $msg = '您没有权限访问';
    public $errorCode = 11111;
}