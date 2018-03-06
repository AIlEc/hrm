<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/12/7
 * Time: 15:58
 */

namespace app\lib\exception;


class StaffException extends BaseException
{
    public $code = 403;
    public $msg = '所查找的员工信息不存在请检查ID';
    public $errorCode = 50000;
}