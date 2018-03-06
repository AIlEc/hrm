<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/12/19
 * Time: 15:55
 */

namespace app\api\validate;


class CompleteInfo extends Base
{
    protected $rule = [
        'username' => 'require|isNotEmpty',
        'account' => 'require|isMobile',
        'sex' => 'require',
        'id_number' => 'require|isIDCard',
        'bank_account' => 'require|isBankCard',
        'bank' => 'require|isOnlyChinese'
    ];
}