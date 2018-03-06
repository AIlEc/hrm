<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/12/7
 * Time: 15:41
 */

namespace app\api\service;


use app\api\model\Commission;
use app\api\model\Employment;
use app\api\model\Staff as StaffModel;
use app\api\model\Image as ImageModel;
use app\api\model\StaffEmployment;
use app\api\model\Takework;
use app\api\model\User;
use app\lib\exception\ScopeException;
use app\lib\exception\StaffException;
use app\lib\exception\SuccessMessage;
use think\Db;
use think\Exception;

class Staff
{
    public function createStaffByAgent($data)
    {
        Db::startTrans();
        try {
            $staffID = $this->saveToStaff($data);
            $this->saveToImage($data['image'], $staffID);
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            throw new Exception();

        }
    }

    private function saveToImage($data, $staffId)
    {
        foreach ($data as $imageURL) {
            $image = new ImageModel();
            $image->from = 0;
            $image->url = $imageURL;
            $image->staff_id = $staffId;
            $image->save();
        }
    }

    private function saveToStaff($data)
    {
        $uid = Token::getCurrentUid();
        $staff = new StaffModel();
        $staff->username = $data['username'];
        $staff->sex = $data['sex'];
        $staff->mobile = $data['mobile'];
        $staff->id_number = $data['id_number'];
        $staff->address = $data['address'];
        $staff->experience = $data['experience'];
        $staff->user_id = $uid;
        $staff->save();
        return $staff->id;
    }

    public function checkInterviewPass($eid, $sid, $status)
    {
        $scope = Token::getCurrentTokenVar('scope');
        if ($scope != 32) {
            throw new ScopeException([
                'msg' => '没有权限操作!'
            ]);
        }
        /*if (intval($status) == 1) {
            $employment = $this->getEmployment();
            $staff = StaffModel::get($sid);
            $staff->status = 2;
            $staff->employment_id = $employment->id;
            $staff->save();
        }*/
        $relation = StaffEmployment::where('staff_id', '=', $sid)
            ->where('employment_id', '=', $eid)
            ->find();
        $relation->status = $status;
        $relation->save();
        return true;
    }

    public function onJobCheck($sid)
    {
        //入职操作，1.员工入职状态修改  2.生成佣金单
        Db::startTrans();
        try {
            $this->changeStaffStatus(2, $sid);
            $this->buildCommissionOrder($sid);
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            throw new Exception($e);
            return false;
        }
    }

    private function changeStaffStatus($status, $sid)
    {
        $user = $this->getUser();
        $employment = $this->getEmployment();
        $staff = StaffModel::get($sid);
        $staff->status = $status;
        $staff->position = $employment->position;
        $staff->company = $employment->company;
        $staff->employment_id = $user->employment_id;
        $staff->save();
    }

    private function buildCommissionOrder($sid)
    {
        $user = $this->getUser();
        $employment = $this->getEmployment();
        $days = $employment->commission_day;
        $getMoneyTime = strtotime("+$days day");
		if ($employment->is_looper == '是') {
            $isLooper = 1;
        } else {
            $isLooper = 0;
        }
        $staff = StaffModel::get($sid);
        $commission = new Commission();
        $commission->onjob_time = time();
        $commission->company = $employment->company;
        $commission->money = $employment->commission;
        $commission->company = $employment->company;
        $commission->getmoney_time = $getMoneyTime;
        $commission->user_id = $staff->user_id;
        $commission->staff_id = $staff->id;
        $commission->staff_name = $staff->username;
        $commission->staff_mobile = $staff->mobile;
        $commission->is_looper = $isLooper;
        $commission->commission_day = $days;
        $commission->save();
        $takework = new Takework();
        $takework->user_id = $staff->user_id;
        $takework->username = $staff->username;
        $takework->company = $employment->company;
        $takework->position = $employment->position;
        $takework->save();
    }

    private function getUser()
    {
        $uid = Token::getCurrentUid();
        $user = User::get($uid);
        return $user;
    }

    private function getEmployment()
    {
        $user = $this->getUser();
        $eid = $user->employment_id;
        $employment = Employment::get($eid);
        return $employment;
    }

    public function leaveJob($sid)
    {
        //离职处理，1.修改员工状态 2.修改佣金单状态 3.修改员工绑定的驻场 4.修改记
        Db::startTrans();
        try {
            $this->dealStaff($sid);
            $this->dealCommission($sid);
            $this->changeRelation($sid);
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            return false;
        }
    }

    private function dealStaff($sid)
    {
        $staff = StaffModel::get($sid);
        $userID = $staff->user_id;
        $staff->status = 4;
        $staff->company = '';
        $staff->position = '';
        $staff->employment_id = '';
        $staff->save();
        $takeword = Takework::get(['user_id' => $userID]);
        $takeword->on_guard = 0;
        $takeword->leave_time = time();
        $takeword->save();
    }

    private function dealCommission($sid)
    {
        $commission = Commission::get(['staff_id' => $sid]);
        $commission->status = -1;
        $commission->save();
    }

    private function changeRelation($sid)
    {
        $user = $this->getUser();
        $eid = $user->employment_id;
        $relation = StaffEmployment::where('employment_id', '=', $eid)
            ->where('staff_id', '=', $sid)
            ->find();
        $relation->is_valid = 0;
        $relation->save();
    }

    public function getStaffOfAuditor()
    {
        $id = Token::getCurrentUid();
        $user = User::get($id);
        $eid = $user->employment_id;
        $employment = Employment::get($eid);
        $staffs = $employment->staffs()
            ->field('staff.id,staff.username,staff.sex,staff.status,staff.mobile,staff.company,staff.address')
            ->where('pivot.is_valid', '=', 1)
            ->where('pivot.status', '=', 1)
            ->select();
        return $staffs;
    }

    public function getOnJobAuditor()
    {
        $id = Token::getCurrentUid();
        $user = User::get($id);
        $eid = $user->employment_id;
        $employment = Employment::get($eid);
        $staffs = $employment->staffs()
            ->field('staff.id,staff.username,staff.company,staff.sex,staff.address,staff.mobile')
			->where('pivot.is_valid', '=', 1)
			->where('pivot.status', '=', 1)
            ->where('staff.status', 'eq', 2)
            ->select();
        return $staffs;
    }

    public function getLayOffStaff()
    {
        $id = Token::getCurrentUid();
        $user = User::get($id);
        $eid = $user->employment_id;
        $employment = Employment::get($eid);
        $staffs = $employment->staffs()
            ->field('staff.id,staff.username,staff.sex,staff.address,staff.mobile')
			->where('pivot.status', '=', 1)
            ->where('pivot.is_valid', '=', 1)
            ->where('staff.status', 'neq', 2)
            ->select();
        return $staffs;
    }

    public function applyStaffToEmployment($eid, $staffIDs = [])
    {
        Db::startTrans();
        try {
            foreach ($staffIDs as $staffID) {
                $this->changeStaffState($staffID);
                $this->createRelationStatus($eid, $staffID);
            }
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            return false;
        }

    }

    //面试状态
    private function changeStaffState($staffID)
    {
        $staff = StaffModel::get($staffID);
        $staff->status = 1;
        $staff->save();
    }

    private function createRelationStatus($eid, $staffID)
    {
        $relation = new StaffEmployment();
        $relation->employment_id = $eid;
        $relation->staff_id = $staffID;
        $relation->save();
    }

    public static function getStaffByStatus($status)
    {
        $id = Token::getCurrentUid();
        $user = User::get($id);
        $result = $user->staffs()
            ->field('username,position,address,company,sex,mobile,id')
            ->where('status', '=', $status)
            ->select();
        return $result;
    }

    public function addStaffByAuditor($data)
    {
        Db::startTrans();
        try {
            $id = $this->doAuditorStaff($data);
            $this->saveToImg($id, $data['image']);
            $this->doRelation($id);
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            throw new StaffException([
                'msg' => '操作失败'
            ]);
        }
    }

    private function doAuditorStaff($data)
    {
        $staff = new StaffModel();
        $staff->username = $data['username'];
        $staff->sex = $data['sex'];
        $staff->mobile = $data['mobile'];
        $staff->address = $data['address'];
        $staff->experience = $data['experience'];
        $staff->save();
        return $staff->id;
    }

    private function saveToImg($staffID, $imgs = [])
    {
        foreach ($imgs as $img) {
            $image = new ImageModel();
            $image->url = $img;
            $image->url = 0;
            $image->staff_id = $staffID;
            $image->save();
        }
    }

    private function doRelation($staffID)
    {
        $uid = Token::getCurrentUid();
        $user = User::get($uid);
        $eid = $user->employment_id;
        $relation = new StaffEmployment();
        $relation->employment_id = $eid;
        $relation->staff_id = $staffID;
        $relation->status = 1;
        $relation->save();
    }

}