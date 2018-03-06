<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/12/15
 * Time: 11:18
 */

namespace app\api\service;

use app\api\model\Message as MessageModel;
use app\lib\exception\MessageException;
use think\Cache;
use think\Loader;

Loader::import('Alidayu.api_demo.SmsDemo');

class Message
{
    public static function sendVerifyCode($mobile, $template = '')
    {
        $randNum = rand(1000, 9999);
        $content = '验证码:'.$randNum.',您正在登录注册,5分钟内有效,若非本人操作,请勿泄密';

        $smsOperation = new \SmsDemo();
        $result = $smsOperation::sendSms($mobile, $randNum, $template);
        if ($result->Code != 'OK') {
            throw new MessageException([
                'msg' => '获取验证码失败'
            ]);
        }

        try {
            $message = new MessageModel();
            $message->mobile = $mobile;
            $message->content = $content;
            $message->request_id = $result->RequestId;
            $message->biz_id = $result->BizId;
            $message->code = $randNum;
            $message->save();
        } catch (\Exception $e) {
            throw new MessageException([
                'msg' => '操作失败'
            ]);
        }

        Cache::set($mobile, $randNum, 300);
        return true;
    }
    
}