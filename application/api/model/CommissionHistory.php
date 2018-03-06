<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/12/14
 * Time: 11:40
 */

namespace app\api\model;


use think\Model;

class CommissionHistory extends Model
{
    protected $autoWriteTimestamp = true;
    protected $hidden = ['update_time', 'delete_time','id'];
}