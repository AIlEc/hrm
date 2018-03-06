<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/11/28
 * Time: 10:21
 */

namespace app\lib\exception;


class EmploymentException extends BaseException
{
    public $code = 404;
    public $msg = '指定招聘信息无效，请检查ID';
    public $errorCode = 20000;
}