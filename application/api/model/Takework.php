<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/12/14
 * Time: 15:00
 */

namespace app\api\model;


use think\Model;

class Takework extends Model
{
    protected $autoWriteTimestamp = true;
    protected $hidden = ['update_time','delete_time','user_id'];
}