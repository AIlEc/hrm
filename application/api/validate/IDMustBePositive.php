<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/11/29
 * Time: 17:48
 */

namespace app\api\validate;


class IDMustBePositive extends Base
{
    protected $rule = [
        'id' => 'require|isPositiveInteger'
    ];
}