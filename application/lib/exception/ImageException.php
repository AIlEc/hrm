<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/12/6
 * Time: 15:32
 */

namespace app\lib\exception;


class ImageException extends BaseException
{
    public $code = 404;
    public $msg = '指定图片ID无效';
    public $errorCode = 40000;
}