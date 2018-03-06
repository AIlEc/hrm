<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/11/29
 * Time: 10:17
 */

namespace app\api\service;


use app\api\model\EmploymentDetail;
use app\lib\exception\EmploymentException;
use think\Db;
use app\api\model\Employment as EmploymentModel;
use think\Exception;

class Employment
{
    public function createEmployment($data)
    {
        Db::startTrans();
        try {
            $id = $this->saveToEmploymentDetail($data);
            $this->saveToEmployment($data, $id);
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            throw new Exception($e);
            return false;
        }
    }

    private function saveToEmployment($data, $id)
    {
        $employment = new EmploymentModel();
        $employment->city = $data['city'];
        $employment->salary = $data['salary'];
        $welfareStr = preg_replace("/(，)/", ",", $data['welfare']);
        $welfareArray = explode(',', $welfareStr);
        $welfare = json_encode($welfareArray);
        $employment->welfare = $welfare;
        $employment->commission = $data['commission'];
        $employment->company = $data['company'];
        $employment->people_num = $data['people_num'];
        $employment->detail_id = $id;
        $employment->commission_day = $data['commission_day'];
        $employment->is_looper = $data['is_looper'];
        $employment->status = $data['status'];
        $employment->position = $data['position'];
        $employment->site = $data['site'];
        $employment->interview_date = $data['interview_date'];
        $employment->interview_time = $data['interview_time'];
        $employment->save();
        return true;
    }

    private function saveToEmploymentDetail($data)
    {
        $employmentDetail = new EmploymentDetail();
        $employmentDetail->content = $data['content'];
        $employmentDetail->save();
        return $employmentDetail->id;
    }

    public function updateEmployment($data)
    {
        Db::startTrans();
        try {
            $this->updateToEmploymentDetail($data);
            $this->updateToEmployment($data);
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
			throw new Exception($e);
            return false;
        }
    }

    private function updateToEmploymentDetail($data)
    {
        $employmentDetail = EmploymentDetail::get($data['detail_id']);
        $employmentDetail->content = $data['content'];
        $employmentDetail->save();
        return true;
    }

    private function updateToEmployment($data)
    {
        $employment = EmploymentModel::get($data['id']);
        $employment->city = $data['city'];
        $employment->salary = $data['salary'];
        $welfareStr = preg_replace("/(，)/", ",", $data['welfare']);
        $welfareArray = explode(',', $welfareStr);
        $welfare = json_encode($welfareArray);
        $employment->welfare = $welfare;
        $employment->company = $data['company'];
        $employment->people_num = $data['people_num'];
        $employment->detail_id = $data['detail_id'];
        $employment->commission_day = $data['commission_day'];
        $employment->is_looper = $data['is_looper'];
        $employment->status = $data['status'];
        $employment->position = $data['position'];
        $employment->commission = $data['commission'];
        $employment->site = $data['site'];
        $employment->interview_date = $data['interview_date'];
        $employment->interview_time = $data['interview_time'];
        $employment->save();
        return true;
    }

    public static function delEmployment($id)
    {
        $employment = EmploymentModel::get($id);
        $detailID = $employment->detail_id;
        Db::startTrans();
        try {
            EmploymentModel::destroy($id);
            EmploymentDetail::destroy($detailID);
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            return false;
        }
    }

    public function batchDelEmployment($ids)
    {
        $employments = EmploymentModel::getEmploymentInIDS($ids);
        $detailIDData = array_column($employments, 'detail_id');
        $detailIDS = implode(',', $detailIDData);
        Db::startTrans();
        try {
            EmploymentModel::destroy($ids);
            EmploymentDetail::destroy($detailIDS);
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            return false;
        }
    }
}