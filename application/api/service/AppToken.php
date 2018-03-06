<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/11/28
 * Time: 11:37
 */

namespace app\api\service;


use app\api\model\ThirdApp;
use app\lib\exception\TokenException;
use think\Cache;
use think\Request;

class AppToken extends Token
{
    public function get($ac, $se)
    {
        //检查ac,se
        $app = ThirdApp::check($ac, $se);
        if (!$app) {
            throw new TokenException([
                'msg' => '授权失败',
                'errorCode' => 10003
            ]);
        } else {
            $values = [
                'scope' => $app->scope,
                'uid' => $app->id
            ];
            $request = Request::instance();
            $app->login_ip = $request->ip();
            $app->setInc('login_time');
            $app->save();
            $token = $this->saveToCache($values);
            return $token;
        }
    }

    private function saveToCache($values)
    {
        $token = self::generateToken();
        $expire_in = config('secure.token_expire_in');
        $result = Cache::set($token, json_encode($values), $expire_in);
        if (!$result) {
            throw new TokenException([
                'msg' => '服务器缓存异常',
                'errorCode' => 10005
            ]);
        }
        return $token;
    }
}