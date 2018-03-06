<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/11/28
 * Time: 11:47
 */

namespace app\api\model;


use think\Model;

class Base extends Model
{
    protected $hidden = [
        'update_time','delete_time'
    ];
}