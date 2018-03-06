<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/11/28
 * Time: 11:28
 */

namespace app\api\validate;


class Token extends Base
{
    protected $rule = [
        'ac' => 'require|isNotEmpty',
        'se' => 'require|isNotEmpty'
    ];
}