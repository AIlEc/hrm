<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/12/14
 * Time: 10:15
 */

namespace app\api\controller\v1;


use app\api\controller\Base;
use app\api\model\CommissionHistory;
use app\api\service\Token;
use app\api\model\Commission as CommissionModel;
use app\api\service\Commission as CommissionService;
use app\api\validate\IDMustBePositive;
use app\lib\exception\CommissionException;
use app\lib\exception\SuccessMessage;

class Commission extends Base
{
    //@param 'id'佣金单的id
    public function settleCommission($id)
    {
        (new IDMustBePositive())->goCheck();
        $commissionService = new CommissionService();
        $result = $commissionService->settleCommissionOrder($id);
        if (!$result) {
            throw new CommissionException([
                'msg' => '操作失败'
            ]);
        }
        return new SuccessMessage();
    }

    /**
     * @api 'commission'
     * @param  人员的ID，查询相关联
     * @return array|false|\PDOStatement|string|\think\Collection
     * @throws \app\lib\exception\ParameterException
     */
    public function getCommissionByID()
    {
        $uid = Token::getCurrentUid();
        $result = CommissionModel::getCommissionLists($uid);
        if (!$result) {
            return [];
        }
        return $result;
    }

    /**
     * @api '/commission/history'
     * @return array|false|static[]
     * @throws \app\lib\exception\ParameterException
     */
    public function getCommissionHistory()
    {
        $uid = Token::getCurrentUid();
        $result = CommissionHistory::all(['user_id' => $uid]);
        if (!$result) {
            return [];
        }
        return $result;
    }

    /**
     * 代理商获取佣金接口
     * @return array|false|\PDOStatement|string|\think\Collection
     * @throws \app\lib\exception\ParameterException
     */
    public function getAllValidCommission()
    {
        $uid = Token::getCurrentUid();
        $result = CommissionModel::getAllValidCommission($uid);
        if (!$result) {
            return [
                'code' => 201,
                'money'=> 0
            ];
        }
        return [
            'code' => 201,
            'money'=> $result
        ];
    }

    /**
     * @api '/commission/houses'
     * @return SuccessMessage
     * @throws CommissionException
     */
    public function commissionAllValid()
    {
        $commissionService = new CommissionService();
        $commissionService->settleAllCommission();
        return new SuccessMessage([
			'msg' => '提取佣金成功'
		]);
    }

}