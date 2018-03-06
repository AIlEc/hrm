<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/12/14
 * Time: 10:55
 */

namespace app\lib\exception;


class CommissionException extends BaseException
{
    public $code = 404;
    public $msg = '指定订单无效，请检查ID';
    public $errorCode = 60000;
}