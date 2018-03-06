<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/12/5
 * Time: 16:55
 */

namespace app\lib\exception;


class ThirdException extends BaseException
{
    public $code = 404;
    public $msg = '找不到后台管理用户';
    public $errorCode = 40000;
}