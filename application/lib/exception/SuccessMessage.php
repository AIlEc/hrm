<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/11/29
 * Time: 10:44
 */

namespace app\lib\exception;


class SuccessMessage extends BaseException
{
    public $code = 200;
    public $msg = '成功';
    public $errorCode = '';
}