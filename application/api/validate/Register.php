<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/12/4
 * Time: 16:52
 */

namespace app\api\validate;


class Register extends Base
{
    protected $rule = [
        'username' => 'require|isNotEmpty',
        'account' => 'require|isMobile',
        'password' => 'require|isNotEmpty',
        'sex' => 'require',
        'verify_code' => 'require|isNotEmpty'
    ];
}