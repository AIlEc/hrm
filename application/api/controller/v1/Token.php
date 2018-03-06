<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/11/28
 * Time: 11:21
 */

namespace app\api\controller\v1;

use app\api\service\AppToken;
use app\api\service\UserToken;
use app\api\validate\Login;
use app\api\validate\Token as TokenValidate;

class Token
{
    /**
     * 第三方应用获取令牌
     * @url /app_token
     * @POST ac=:ac se=:secret
     */
    public function getAppToken($ac = '', $se = '')
    {
        (new TokenValidate())->goCheck();
        $app = new AppToken();
        $token = $app->get($ac, $se);
        return [
            'token' => $token
        ];
    }

    /**
     * 用户登录获取令牌
     * 用户权限分两种：驻场人员、代理人
     */
    public function getUserToken($ac = '', $ps = '')
    {
        (new Login())->goCheck();
        $user = new UserToken();
        $result = $user->get($ac, $ps);
        return $result;
    }
}