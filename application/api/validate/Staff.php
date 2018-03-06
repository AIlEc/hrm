<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/12/7
 * Time: 15:21
 */

namespace app\api\validate;


class Staff extends Base
{
    protected $rule = [
        'sex' => 'require',
        'mobile' => 'require|isMobile',
        'id_number' => 'require|isIDCard',
        'address' => 'require|isNotEmpty',
        'experience' => 'require|isNotEmpty'
    ];
}