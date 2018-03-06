<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/12/5
 * Time: 14:46
 */

namespace app\api\validate;


class Auditor extends Base
{
    protected $rule = [
        'eid' => 'require|isPositiveInteger',
        'account' => 'require|isMobile'
    ];
}