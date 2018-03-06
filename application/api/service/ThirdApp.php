<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/12/5
 * Time: 16:51
 */

namespace app\api\service;


use app\api\model\ThirdApp as ThirdModel;
use app\lib\exception\ThirdException;

class ThirdApp
{
    public static function createThird($data)
    {
        $app = ThirdModel::checkAccount($data['account']);
        if($app){
           throw new ThirdException([
               'code' => 403,
               'msg' => '管理员不可重复添加'
           ]);
        }
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