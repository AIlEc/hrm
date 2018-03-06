<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/12/15
 * Time: 11:04
 */

namespace app\api\controller\v1;


use app\api\controller\Base;
use app\api\model\User;
use app\api\validate\Mobile;
use app\api\service\Message as MessageService;
use app\lib\exception\MessageException;
use app\lib\exception\SuccessMessage;

class Message extends Base
{
    /**
     * 登录注册发送验证码
     * @return SuccessMessage
     * @throws MessageException
     * @throws \app\lib\exception\ParameterException
     */
    public function sendVerifyCode()
    {
        (new Mobile())->goCheck();
        $mobile = input('post.account');
        $result = MessageService::sendVerifyCode($mobile, 'SMS_117520488');
        if (!$result) {
            throw new MessageException([
                'msg' => '获取验证码失败'
            ]);
        }
        return new SuccessMessage();
    }

    //找回密码发送验证码
    public function sendVerifyCodeForPSW()
    {
        (new Mobile())->goCheck();
        $mobile = input('post.account');
        $user = User::where('account','=',$mobile)
            ->find();
        if(!$user){
            throw new MessageException([
                'code' => 400,
                'msg' => '此账号暂未注册'
            ]);
        }
        $result = MessageService::sendVerifyCode($mobile, 'SMS_120125829');
        if (!$result) {
            throw new MessageException([
                'msg' => '获取验证码失败'
            ]);
        }
        return new SuccessMessage();
    }

}