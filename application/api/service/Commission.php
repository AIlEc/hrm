<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/12/14
 * Time: 10:13
 */

namespace app\api\service;


use app\api\model\Commission as CommissionModel;
use app\api\model\CommissionHistory;
use app\api\model\Staff;
use app\api\model\User;
use app\lib\exception\CommissionException;
use think\Db;

class Commission
{
    private $id;

    public function settleCommissionOrder($id)
    {
        //1.检查订单状态
        //  2.将佣金增加到用户
        // 3.1查看订单是否再次生效
        // 3.2查看人员状态是否已经离职，离职不能再次生成,生成佣金领取记录
        //如果没有了，就将此单子结束，如果还有，则继续，计算下次领取佣金时间
        $commission = CommissionModel::get($id);
        if (!$commission) {
            throw new CommissionException();
        }
        if ($commission->status != 0) {
            throw new CommissionException([
                'msg' => '佣金单已经支付过或不生效'
            ]);
        }
        $this->id = $id;
        Db::startTrans();
        try {
            $this->dealUserAccount($commission->user_id, $commission->money);
            $this->dealCommissionOrder();
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            return false;
        }
    }

    private function dealUserAccount($userID, $money)
    {
        $user = User::get($userID);
        $commissionMoney = bcadd($user->backerage, $money, 2);
        $user->backerage = $commissionMoney;
        $user->save();
    }

    private function dealCommissionOrder()
    {
        $commission = CommissionModel::get($this->id);
        if ($commission->is_looper == 0) {
            $commission->status = 1;
            $commission->save();
            $this->createHistory($commission->user_id, $commission->money);
            return true;
        }
        $staffID = $commission->staff_id;
        $staff = Staff::get($staffID);
        //员工不是在职状态
        if ($staff->status != 2) {
            $commission->status = 1;
            $commission->save();
            $this->createHistory($commission->user_id, $commission->money);
            return true;
        }
        //重新计算此订单的领取佣金时间
        $days = $commission->commission_day;
        $getMoneyTime = strtotime("+$days day");
        $commission->getmoney_time = $getMoneyTime;
        $commission->save();
        $this->createHistory($commission->user_id, $commission->money);
        return true;
    }

    private function createHistory($userID, $money)
    {
        $commissionHistory = new CommissionHistory();
        $commissionHistory->user_id = $userID;
        $commissionHistory->money = $money;
        $commissionHistory->save();
    }

    public function settleAllCommission()
    {
        $uid = Token::getCurrentUid();
        $cIDs = CommissionModel::getAllValidID($uid);
        if(!$cIDs){
            throw new CommissionException([
                'msg' => '暂无佣金可以提取'
            ]);
        }
        foreach($cIDs as $cID){
            $this->settleCommissionOrder($cID);
        }
        return true;
    }
}