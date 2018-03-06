<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/12/15
 * Time: 11:14
 */

namespace app\api\validate;


class Mobile extends Base
{
    protected $rule = [
        'account' => 'require|isMobile'
    ];
}