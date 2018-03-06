<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/12/4
 * Time: 17:53
 */

namespace app\lib\exception;


class UserException extends BaseException
{
    public $code = 404;
    public $msg = '所查询的用户并不存在';
    public $errorCode = 30001;
}