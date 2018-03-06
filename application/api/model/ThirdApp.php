<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/11/28
 * Time: 15:31
 */

namespace app\api\model;


class ThirdApp extends Base
{
    public static function check($ac, $se)
    {
        $app = self::where('app_id', '=', $ac)
            ->where('app_secret', '=', $se)
            ->find();
        return $app;
    }

    //检查用户，避免重复插入
    public static function checkAccount($ac)
    {
        $app = self::where('app_id', '=', $ac)
            ->find();
        if(!$app){
            return false;
        }
        return true;
    }
}