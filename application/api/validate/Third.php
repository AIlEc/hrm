<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/12/5
 * Time: 16:48
 */

namespace app\api\validate;


class Third extends Base
{
    protected $rule = [
        'account' => 'require|isNotEmpty',
        'password' => 'require|isNotEmpty'
    ];
}