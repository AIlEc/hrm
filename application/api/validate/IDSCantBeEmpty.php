<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/12/1
 * Time: 11:26
 */

namespace app\api\validate;


class IDSCantBeEmpty extends Base
{
    protected $rule = [
        'ids' => 'require|isNotEmpty'
    ];
}