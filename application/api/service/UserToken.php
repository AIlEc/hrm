<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/12/11
 * Time: 15:17
 */

namespace app\api\service;


use app\api\model\User;
use app\lib\enum\Scope;
use app\lib\exception\ScopeException;
use app\lib\exception\TokenException;
use think\Cache;

class UserToken extends Token
{
    public function get($ac, $ps)
    {
        $user = User::checkUser($ac, $ps);
        if(!$user){
            throw new TokenException([
                'msg' => '用户名或密码输入错误',
                'errorCode' => 10003
            ]);
        }else{
            $scope = $user->scope;
            $uid = $user->id;
            $values = [
                'scope' => $scope,
                'uid' => $uid
            ];
            $token = $this->saveToCache($values);
            if($scope == Scope::Agent){
                $client = 'Agent';
            } elseif ($scope == Scope::Auditor){
                $client = 'Auditor';
            }else{
                throw new ScopeException();
            }
            return [
                'client' => $client,
                'current_time' => time(),
                'token' => $token
            ];
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