<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/11/27
 * Time: 17:49
 */

namespace app\api\validate;


class Employment extends Base
{
    protected $rule = [
        'city' => 'require|isNotEmpty',
        'commission_day' => 'require|isPositiveInteger',
        'company' => 'require|isNotEmpty',
        'content' => 'require|isNotEmpty',
        'salary' => 'require|isNotEmpty',
        'people_num' => 'require|isPositiveInteger',
        'position' => 'require|isNotEmpty'
    ];
}