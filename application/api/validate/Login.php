<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/12/11
 * Time: 15:07
 */

namespace app\api\validate;


class Login extends Base
{
    protected $rule = [
        'ac' => 'require|isNotEmpty',
        'ps' => 'require|isNotEmpty'
    ];
}