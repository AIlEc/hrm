<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/12/13
 * Time: 15:06
 */

namespace app\api\model;


use think\Model;

class Commission extends Model
{
    protected $autoWriteTimestamp = true;
    protected $hidden = ['user_id', 'status', 'staff_id', 'create_time', 'update_time', 'delete_time'];

    public static function getCommissionLists($uid)
    {
        $result = self::where('status', '=', 0)
            ->where('user_id', '=', $uid)
            ->select();
        return $result;
    }

    public static function getAllValidCommission($uid)
    {
        $result = self::where('status', '=', 0)
            ->where('user_id', '=', $uid)
            ->where('getmoney_time', 'st', time())
            ->sum('money');
        return $result;
    }

    public static function getAllValidID($uid)
    {
        $result = self::field('id')
            ->where('status', '=', 0)
            ->where('user_id','=',$uid)
            ->where('getmoney_time', 'st', time())
            ->select();
        return $result;
    }
}