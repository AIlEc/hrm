<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/11/29
 * Time: 14:39
 */

namespace app\api\validate;


class PagingParameter extends Base
{
    protected $rule = [
        'page' => 'isPositiveInteger',
        'size' => 'isPositiveInteger'
    ];

    protected $message = [
        'page' => '分页参数必须是正整数',
        'size' => '分页参数必须是正整数'
    ];
}