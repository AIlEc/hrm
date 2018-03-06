<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/12/7
 * Time: 15:03
 */

namespace app\api\controller\v1;


use app\api\controller\Base;
use app\api\model\Employment;
use app\api\model\StaffEmployment;
use app\api\model\Staff as StaffModel;
use app\api\model\User;
use app\api\service\Staff as StaffService;
use app\api\service\Token;
use app\api\validate\IDMustBePositive;
use app\api\validate\Staff as StaffValidate;
use app\lib\exception\ParameterException;
use app\lib\exception\StaffException;
use app\lib\exception\SuccessMessage;
use think\Cache;
use think\Exception;
use think\Request;

class Staff extends Base
{
    /**
     * @api '/staff/add'
     * @return SuccessMessage
     * @throws StaffException
     * @throws \app\lib\exception\ParameterException
     * @throws \think\Exception
     */
    public function createStaff()
    {
        (new StaffValidate())->goCheck();
        $data = input('post.');
        $staffService = new StaffService();
        $result = $staffService->createStaffByAgent($data);
        if (!$result) {
            throw new StaffException([
                'msg' => '添加人才档案失败'
            ]);
        }
        return new SuccessMessage();
    }

    /**
     * @api 'staff/agent'获取全部的员工的状态
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \app\lib\exception\ParameterException
     */
    public function getStaffOfAgent()
    {
        $id = Token::getCurrentUid();
        $user = User::get($id);
        $result = $user->staffs()
            ->field('username,position,sex,address,company,mobile,id')
            ->select();
        if (!$result) {
            return [];
        }
        return $result;
    }

    /**
     * @return array
     * @throws ParameterException
     * $api 'staff/agent/inter'
     * @param $status 0默认状态，1面试，2录用，3未录
     */
    public function getInterStaff()
    {
        $result = StaffService::getStaffByStatus(1);
        if (!$result) {
            return [];
        }
        return $result;
    }

    /**
     * @api 'staff/hire'获取已录用的接口
     * @return array
     */
    public function getHireStaff()
    {
        $result = StaffService::getStaffByStatus(2);
        if (!$result) {
            return [];
        }
        return $result;
    }

    /**
     * @api 'layoff'获取未录用的接口
     * @return mixed
     * @throws ParameterException
     */
    public function getLayoffStaff()
    {
        $id = Token::getCurrentUid();
        $user = User::get($id);
        $result = $user->staffs()
            ->where('status', 'neq', 2)//'in' '[0,3,4]'
            ->select();
        return $result;
    }

    /**
     * @api 'staff/agent/no_job'获取可以报名员工的接口
     * @return mixed
     * @throws ParameterException
     */
    public function getNoJobStaff()
    {
        $employment = Employment::get(input('param.eid'));
        $appliedIds = $employment->staffs()
            ->column('staff.id');
        $appliedIds = implode(',', $appliedIds);
        $id = Token::getCurrentUid();
        $user = User::get($id);
        $result = $user->staffs()
            ->where('id', 'not in', $appliedIds)
            ->where('status', 'neq', 2)
            ->select();
        if (!$result) {
            return [];
        }
        return $result;
    }
	
	public function getOnApplyStaff(Request $request)
    {
		$id = Token::getCurrentUid();
        $user = User::get($id);
        $staffIDs = $user->staffs()
            ->where('status', 'eq', 1)//'in' '[0,3,4]'
            ->column('staff.id');
		$staffIds = implode(",", $staffIDs);
        $employment = Employment::get($request->param('eid'));
        $result = $employment->staffs()
				->where('staff.id', 'in', $staffIds)
				->select();
        if(!$result){
            return [];
        }
        return $result;
    }

    /**
     * @api 'staff/auditor'获取全部驻场人员下的员工信息
     * @return array
     */
    public function getStaffOfAuditor()
    {
        $staffService = new StaffService();
        $result = $staffService->getStaffOfAuditor();
        if (!$result) {
            return [];
        }
        return $result;
    }

    /**
     * @api 'staff/auditor'
     * @return array
     */
    public function getStaffOfAuditorOnJob()
    {
        $staffService = new StaffService();
        $result = $staffService->getOnJobAuditor();
        if (!$result) {
            return [];
        }
        return $result;
    }

    public function getStaffOfAuditorsLayOff()
    {
        $staffService = new StaffService();
        $result = $staffService->getLayOffStaff();
        if (!$result) {
            return [];
        }
        return $result;
    }

    /**
     * @qpi 'staff/:id'
     * 人才详情接口
     * @param $id
     * @return $this
     * @throws ParameterException
     * @throws StaffException
     */
    public function getStaffByID($id)
    {
        (new IDMustBePositive())->goCheck();
        $result = StaffModel::get($id)
            ->hidden(['status', 'company', 'position']);
        if (!$result) {
            throw new StaffException([
                'msg' => '暂无此内容,请检查ID'
            ]);
        }
        return $result;
    }

    /**
     * @api 'staff/agent/apply'
     * @post 'eid','sid'数组
     * @return SuccessMessage
     * @throws ParameterException
     */
    public function applyStaffToEmployment()
    {
        $employmentID = input('post.eid');
        $staffIDs = input('post.sid/a');
        if (!is_array($staffIDs)) {
            throw new ParameterException([
                '传入参数格式不正确!'
            ]);
        }
        $staffService = new StaffService();
        $result = $staffService->applyStaffToEmployment($employmentID, $staffIDs);
        if (!$result) {
            throw new StaffException([
                'msg' => '报名失败'
            ]);
        }
        return new SuccessMessage();
    }

    /**
     * @api '/staff/employment/:id'
     * @param $id
     * @return mixed
     * @throws ParameterException
     */
    public function getStaffEmployments($id)
    {
        (new IDMustBePositive())->goCheck();
		$result = StaffModel::get($id,['employment']);
		$result->hidden(['id_number','experience','status','company','position']);
		if(!$result){
			return [];
		}
        return $result;
    }

    //status规定 1 通过 ， 2 不通过
    public function checkInterview()
    {
        //招聘信息id跟人员id
        $eid = input('post.eid');
        $sid = input('post.sid');
        $status = input('post.status');
        $staffService = new StaffService();
        $result = $staffService->checkInterviewPass($eid, $sid, $status);
        if (!$result) {
            throw new StaffException([
                'msg' => '操作失败'
            ]);
        }
        return new SuccessMessage();
    }

    /**
     * @api 'staff/on_job'
     * @param 'id' staffID
     * @return SuccessMessage
     * @throws ParameterException
     * @throws StaffException
     * @throws \think\Exception
     */
    public function onJob()
    {
        (new IDMustBePositive())->goCheck();
        $sid = input('post.id');
        $staff = new StaffService();
        $result = $staff->onJobCheck($sid);
        if (!$result) {
            throw new StaffException([
                'msg' => '入职操作失败'
            ]);
        }
        return new SuccessMessage();
    }

    /**
     * @api 'staff/leave_job'
     * @param 'id'  StaffID
     * @return SuccessMessage
     * @throws ParameterException
     * @throws StaffException
     */
    public function leaveJob()
    {
        (new IDMustBePositive())->goCheck();
        $sid = input('post.id');
        $staffService = new StaffService();
        $result = $staffService->leaveJob($sid);
        if (!$result) {
            throw new StaffException([
                'msg' => '操作失败'
            ]);
        }
        return new SuccessMessage();
    }

    /**
     * @api '/staff/auditor/add' 需要的字段信息与代理商天假所需要字段一样
     * @return SuccessMessage
     * @throws ParameterException
     * @throws StaffException
     */
    public function addStaffByAuditorDirectly()
    {
        (new StaffValidate())->goCheck();
        $data = input('post.');
        $staffService = new StaffService();
        $staffService->addStaffByAuditor($data);
        return new SuccessMessage();
    }

    public function delStaffByAuditor()
    {
        try {
            $auditorID = Token::getCurrentUid();
            $user = User::get($auditorID);
            $sid = input('post.sid');
            $relation = StaffEmployment::where('employment_id', '=', $user->employment_id)
                ->where('staff_id', '=', $sid)
                ->find();
            if($relation->is_valid == 0){
                throw new Exception();
            }
            $relation->is_valid = 0;
            $relation->save();
            return new SuccessMessage();
        } catch (\Exception $e) {
            throw new StaffException([
                'msg' => '删除失败',
                'errCode' => 50005
            ]);
        }
    }

}