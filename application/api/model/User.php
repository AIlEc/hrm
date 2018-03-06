<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/12/4
 * Time: 17:07
 */

namespace app\api\model;


use think\Model;

class User extends Model
{
    protected $autoWriteTimestamp = true;

    public function staffs()
    {
        return $this->hasMany('Staff', 'user_id');
    }

    public static function getAllAgents($page, $limit)
    {
        $result = self::where('scope', '=', 16)
            ->where('status', '=', 1)
            ->order('create_time asc')
            ->paginate($limit, false, [
                'page' => $page
            ]);
        return $result;
    }

    public static function getAllAuditor($page, $limit)
    {
        $result = self::field('id,account,username,create_time')
            ->where('scope', '=', 32)
            ->where('status', '=', 1)
            ->order('create_time asc')
            ->paginate($limit, false, [
                'page' => $page
            ]);
        return $result;
    }

    public function getSexAttr($value)
    {
        $sex = [0 => "女", 1 => "男"];
        return $sex[$value];
    }

    public static function checkUser($ac, $ps)
    {
        $psw = md5($ps);
        $user = self::where('account', '=', $ac)
            ->where('password', '=', $psw)
            ->find();
        return $user;
    }
}