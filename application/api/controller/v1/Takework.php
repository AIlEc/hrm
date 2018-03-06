<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/12/14
 * Time: 15:19
 */

namespace app\api\controller\v1;


use app\api\controller\Base;
use app\api\service\Token;
use app\api\model\Takework as TakeworkModel;

class Takework extends Base
{
    /**
     * @api '/takework'获取员工离职入职消息
     * @return array|null|static
     * @throws \app\lib\exception\ParameterException
     */
    public function getTakeWorks()
    {
        $uid = Token::getCurrentUid();
        $result = TakeworkModel::all(['user_id' => $uid]);
        if(!$result){
            return [];
        }
        return $result;
    }
}