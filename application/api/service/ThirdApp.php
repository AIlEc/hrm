<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/12/5
 * Time: 16:51
 */

namespace app\api\service;


use app\api\model\ThirdApp as ThirdModel;
class ThirdApp
{
    public static function createThird($data)
    {
        $third = new ThirdModel();
        $third->app_id = $data['account'];
        $third->app_secret = $data['password'];
        $third->save();
        return $third->id;
    }

    public static function updateThird($data)
    {
        try{
            $third = ThirdModel::get($data['id']);
            $third->app_id = $data['account'];
            $third->app_secret = $data['password'];
            $third->save();
            return true;
        }catch(\Exception $e){
            return false;
        }
    }
}