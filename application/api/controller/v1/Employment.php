<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/11/27
 * Time: 17:31
 */

namespace app\api\controller\v1;

use app\api\service\Employment as EmploymentService;
use app\api\validate\Employment as EmploymentValidate;
use app\api\model\Employment as EmploymentModel;
use app\api\validate\IDMustBePositive;
use app\api\validate\IDSCantBeEmpty;
use app\api\validate\PagingParameter;
use app\lib\exception\EmploymentException;
use app\lib\exception\SuccessMessage;

class Employment
{
    /**
     * @api '/employment_add'
     * @param [city,commission_day,company,content,is_looper,people_num,salary,status]
     * @throws EmploymentException
     * @return SuccessMessage
     */
    public function createEmployment()
    {
        $validate = new EmploymentValidate();
        $validate->goCheck();
        $data = input('post.');
        $employmentService = new EmploymentService();
        $result = $employmentService->createEmployment($data);
        if (!$result) {
            throw new EmploymentException([
                'code' => 404,
                'msg' => '新增招聘信息失败',
                'errorCode' => 20001
            ]);
        }
        return new SuccessMessage([
            'code' => 201
        ]);
    }

    /**
     * @api '/employment/paginate'
     */
    public function getEmploymentPaginate($page = 1, $limit = 15)
    {
        $validate = new PagingParameter();
        $validate->goCheck();
        $pagingEmployment = EmploymentModel::getAllEmployments($page, $limit);
        if (!$pagingEmployment) {
            return [
                'code' => 0,
                'count' => 0,
                'current_page' => $pagingEmployment->currentPage(),
                'data' => []
            ];
        }
        return [
            'code' => 0,
            'count' => $pagingEmployment->total(),
            'current_page' => $pagingEmployment->currentPage(),
            'data' => $pagingEmployment->items()
        ];
    }

    /**
     * @api '/del'
     * @param $id
     * @return SuccessMessage
     * @throws EmploymentException
     * @throws \app\lib\exception\ParameterException
     */
    public function deleteEmployment($id)
    {
        (new IDMustBePositive())->goCheck();
        $result = EmploymentService::delEmployment($id);
        if (!$result) {
            throw new EmploymentException([
                'code' => 404,
                'msg' => '删除招聘信息失败'
            ]);
        }
        return new SuccessMessage();
    }

    /**
     * @api 'change_status'
     * @param $id
     * @return SuccessMessage
     * @throws EmploymentException
     * @throws \app\lib\exception\ParameterException
     */
    public function changeEmploymentStatus($id)
    {
        (new IDMustBePositive())->goCheck();
        $employment = EmploymentModel::get($id);
        if ($employment->status == '启用') {
            $employment->status = 0;
            $employment->save();
        } else if ($employment->status == '停用') {
            $employment->status = 1;
            $employment->save();
        } else {
            throw new EmploymentException([
                'code' => 403,
                'msg' => '改变状态失败'
            ]);
        }
        return new SuccessMessage();
    }

    /**
     * @api '/batch_del'
     * @param $ids
     * @return SuccessMessage
     * @throws EmploymentException
     * @throws \app\lib\exception\ParameterException
     */
    public function delBatchEmployments($ids)
    {
        (new IDSCantBeEmpty())->goCheck();
        $employment = new EmploymentService();
        $result = $employment->batchDelEmployment($ids);
        if (!$result) {
            throw new EmploymentException([
                'code' => 403,
                'msg' => '批量删除招聘信息失败'
            ]);
        }
        return new SuccessMessage();
    }

    /**
     * @qpi 'api/:version/employment/:id'
     * @param $id
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws EmploymentException
     * @throws \app\lib\exception\ParameterException
     * 根据id获取
     */
    public function getOneByID($id)
    {
        (new IDMustBePositive())->goCheck();
        $result = EmploymentModel::getEmploymentDetail($id);
        if (!$result) {
            throw new EmploymentException();
        }
        return $result;
    }

    public function updateEmployment()
    {
        (new IDMustBePositive())->goCheck();
        (new EmploymentValidate())->goCheck();
        $data = input('post.');
        $employment = new EmploymentService();
        $result = $employment->updateEmployment($data);
        if (!$result) {
            throw new EmploymentException([
                'code' => 404,
                'msg' => '修改招聘信息失败',
                'errorCode' => 20002
            ]);
        }
        return new SuccessMessage();
    }

}