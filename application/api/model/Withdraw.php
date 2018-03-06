<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/12/19
 * Time: 17:11
 */

namespace app\api\model;


use think\Model;

class Withdraw extends Model
{
    protected $autoWriteTimestamp = true;
	protected $hidden = ['status','user_id','update_time','delete_time'];

    public static function getListsByUser($uid)
    {
        $result = self::where('user_id', '=', $uid)
            ->select();
        return $result;
    }
}