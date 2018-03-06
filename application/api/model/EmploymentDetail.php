<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/11/29
 * Time: 10:20
 */

namespace app\api\model;


class EmploymentDetail extends Base
{
    protected $autoWriteTimestamp = true;

    public function getContentAttr($value)
    {
        return htmlspecialchars_decode($value);
    }
}