<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/12/5
 * Time: 16:37
 */

namespace app\api\controller\v1;


use app\api\service\ThirdApp as ThirdService;
use app\api\validate\IDMustBePositive;
use app\api\validate\Third as ThirdValidate;
use app\lib\exception\SuccessMessage;
use app\lib\exception\ThirdException;
use app\api\model\ThirdApp as ThirdModel;

class ThirdApp
{
    public function createThird()
    {
        (new ThirdValidate())->goCheck();
        $data = input('post.');
        $result = ThirdService::createThird($data);
        if (!$result) {
            throw new ThirdException([
                'code' => 403,
                'msg' => '添加管理员失败'
            ]);
        }
        return new SuccessMessage();
    }

    public function deleteThird()
    {
        (new IDMustBePositive())->goCheck();
        $id = input('post.id');
        ThirdModel::destroy($id);
        return new SuccessMessage();
    }

    public function updateThird()
    {
        (new ThirdValidate())->goCheck();
        $data = input('post.');
        $result = ThirdService::updateThird($data);
        if (!$result) {
            throw new ThirdException([
                'msg' => '修改管理员信息失败'
            ]);
        }
        return new SuccessMessage();
    }

    public function getOneByID($id)
    {
        (new IDMustBePositive())->goCheck();
        $result = ThirdModel::get($id);
        $result->hidden(['app_secret','scope','scope_description']);
        if (!$result) {
            throw new ThirdException([
                'msg' => '此用户不存在'
            ]);
        }
        return $result;
    }

    public function getAllThird()
    {
        $allThird = ThirdModel::all();
        if(!$allThird){
            return [
                'code' => 0,
                'data' => [],
                'count' => 0
            ];
        }
        return [
            'code' => 0,
            'data' => $allThird,
            'count' => count($allThird)
        ];
    }
}