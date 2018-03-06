<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/12/4
 * Time: 16:37
 */

namespace app\api\controller\v1;


use app\api\controller\Base;
use app\api\model\User as UserModel;
use app\api\model\Withdraw;
use app\api\service\Token;
use app\api\service\User as UserService;
use app\api\validate\Auditor;
use app\api\validate\CompleteInfo;
use app\api\validate\IDMustBePositive;
use app\api\validate\IDSCantBeEmpty;
use app\api\validate\PagingParameter;
use app\api\validate\Register;
use app\lib\enum\Scope;
use app\lib\exception\SuccessMessage;
use app\lib\exception\UserException;
use think\Cache;
use think\Exception;
use think\Request;

class User extends Base
{

    public function getUserDetail()
    {
        $uid = Token::getCurrentUid();
        $result = UserModel::get($uid)
            ->visible(['create_time', 'username', 'account', 'sex', 'id_number', 'bank', 'bank_account', 'backerage', 'complete_status']);
        return $result;
    }

    public function getUserMoney()
    {
        $uid = Token::getCurrentUid();
        $result = UserModel::get($uid);
        $result->visible(['backerage']);
        return $result;
    }

    /**
     * @api '/user/register'
     * @return SuccessMessage
     * @throws UserException
     * @throws \app\lib\exception\ParameterException
     */
    public function agentRegister(Request $request)
    {
        (new Register())->goCheck();
        if (input('post.verify_code') != Cache::get(input('post.account'))) {
            throw new UserException([
                'msg' => '验证码输入错误',
                'errorCode' => 30006
            ]);
        }

        $user = UserModel::checkUniqueAccount($request->param('account'));
        if($user){
            throw new UserException([
                'code' => 403,
                'msg' => '此用户账号已经被注册'
            ]);
        }

        try {
            $user = new UserModel();
            $user->username = $request->param('username');
            $user->account = $request->param('account');
            $user->password = md5($request->param('password'));
            $user->sex = $request->param('sex');
            $user->scope = Scope::Agent;
            $user->save();
        } catch (\Exception $e) {
            throw new UserException([
                'code' => 403,
                'msg' => '用户注册失败',
                'errorCode' => 30003
            ]);
        }
        return new SuccessMessage();
    }

    public function agentComplete()
    {
        (new CompleteInfo())->goCheck();
        $userService = new UserService();
        $userService->updateAgent();
        return new SuccessMessage();
    }

    /**
     * @api 'user/agent_paginate'
     * @param int $page
     * @param int $limit
     * @return array
     * @throws \app\lib\exception\ParameterException
     */
    public function getAgentPaginate($page = 1, $limit = 10)
    {
        (new PagingParameter())->goCheck();
        $usersPaginate = UserModel::getAllAgents($page, $limit);
        if (!$usersPaginate) {
            return [
                'code' => 0,
                'count' => 0,
                'current_page' => $usersPaginate->currentPage(),
                'data' => []
            ];
        }
        return [
            'code' => 0,
            'count' => $usersPaginate->total(),
            'current_page' => $usersPaginate->currentPage(),
            'data' => $usersPaginate->items()
        ];
    }

    public function deleteUser()
    {
        (new IDMustBePositive())->goCheck();
        $id = input('post.id');
        UserModel::destroy($id);
        return new SuccessMessage();
    }

    public function delBatchUsers()
    {
        (new IDSCantBeEmpty())->goCheck();
        $ids = input('post.ids');
        UserModel::destroy($ids);
        return new SuccessMessage();
    }

    /**
     * @api '/user/auditor_register'
     * @return SuccessMessage
     * @throws UserException
     * @throws \app\lib\exception\ParameterException
     */
    public function createAuditor()
    {
        (new Auditor())->goCheck();
        $eid = input('post.eid');
        $account = input('post.account');
        $result = UserService::createAuditor($eid, $account);
        if (!$result) {
            throw new UserException([
                'code' => 403,
                'msg' => '注册驻场人员失败',
                'errorCode' => 30002
            ]);
        }
        return new SuccessMessage();
    }

    public function getAuditorPaginate($page = 1, $limit = 10)
    {
        (new PagingParameter())->goCheck();
        $auditorPaginate = UserModel::getAllAuditor($page, $limit);
        if (!$auditorPaginate) {
            return [
                'code' => 0,
                'count' => 0,
                'current_page' => $auditorPaginate->currentPage(),
                'data' => []
            ];
        }
        return [
            'code' => 0,
            'count' => $auditorPaginate->total(),
            'current_page' => $auditorPaginate->currentPage(),
            'data' => $auditorPaginate->items()
        ];
    }

    public function withdraw()
    {
        $userService = new UserService();
        $userService->withdrawDeposit();
        return new SuccessMessage();
    }

    public function withdrawListOfUser()
    {
        $uid = Token::getCurrentUid();
        $result = Withdraw::getListsByUser($uid);
        if (!$result) {
            return [];
        }
        return $result;
    }

    //检查验证码是否正确
    public function checkVerify()
    {
        if (input('post.verify_code') != Cache::get(input('post.account'))) {
            throw new UserException([
                'msg' => '验证码输入错误',
                'errorCode' => 30006
            ]);
        }
        return new SuccessMessage();
    }

    //用户修改密码 要使用header？不可能吧
    //post 'account','password'
    public function changePassword(Request $request)
    {
        $account = UserModel::where('account', '=', $request->param('account'))
            ->find();
        if (!$account) {
            throw new UserException([
                'code' => 400,
                'msg' => '暂无此用户'
            ]);
        }
        try {
            $account->password = md5($request->param('password'));
            $account->save();
        } catch (\Exception $e) {
            throw new UserException([
                'code' => 403,
                'msg' => '修改密码失败'
            ]);
        }
        return new SuccessMessage();
    }
}