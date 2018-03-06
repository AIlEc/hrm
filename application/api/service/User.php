<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/12/4
 * Time: 17:24
 */

namespace app\api\service;


use app\api\model\User as UserModel;
use app\api\model\Withdraw;
use app\lib\enum\Scope;
use app\api\model\Image as ImageModel;
use app\lib\exception\UserException;
use think\Db;
use think\Exception;
use think\Log;
use think\Request;

class User
{
    protected $request;
    protected $uid;
    protected $money;
    protected $user;

    function __construct()
    {
        $this->uid = Token::getCurrentUid();
        $this->request = Request::instance();
    }

    public function updateAgent()
    {
        Db::startTrans();
        try {
            $this->updateToUser();
            if (!empty($this->request->param('image'))) {
                $this->saveToImage();
            }
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            throw new UserException([
                'code' => 400,
                'msg' => '用户完善信息失败',
                'errorCode' => 30007
            ]);
        }
    }

    private function updateToUser()
    {
        $user = UserModel::get($this->uid);
        $user->username = $this->request->param('username');
        $user->account = $this->request->param('account');
        $user->sex = $this->request->param('sex');
        $user->id_number = $this->request->param('id_number');
        $user->bank = $this->request->param('bank');
        $user->bank_account = $this->request->param('bank_account');
        $user->complete_status = 1;
        $user->save();
    }

    private function saveToImage()
    {
        $imageURLs = explode($this->request->param('image'), ',');
        foreach ($imageURLs as $ImageURL) {
            $image = new ImageModel();
            $image->from = 0;
            $image->url = $ImageURL;
            $image->user_id = $this->uid;
            $image->save();
        }
    }

    /**
     * @param
     * @return mixed
     * 后台注册生成驻场人员账号
     * 密码为自动的123456
     */
    public static function createAuditor($eid, $account)
    {
        $user = new UserModel();
        $user->username = '驻场人员';
        $user->employment_id = $eid;
        $user->account = $account;
        $user->password = md5(config('secure.base_password'));
        $user->scope = Scope::Auditor;
        $user->save();
        return $user->id;
    }


    public function withdrawDeposit()
    {
        Db::startTrans();
        try{
            self::cleanUsersBackerage();
            self::createWithdraw();
            Db::commit();
            return true;
        }catch(\Exception $e){
            Db::rollback();
            throw new UserException([
				'code' => 400,
                'msg' => '佣金提取失败',
                'errorCode' => 30008
            ]);
        }
    }

    private function cleanUsersBackerage()
    {
        $user = UserModel::get($this->uid);
        $this->money = $user->backerage;
        if ($user->backerage == 0){
            throw new Exception();
        }
        $user->backerage = 0;
        $user->save();
        $this->user = $user;
    }

    private function createWithdraw()
    {
        $withdraw = new Withdraw();
        $withdraw->money = $this->money;
        $withdraw->username = $this->user->username;
        $withdraw->user_id = $this->uid;
        $withdraw->save();
    }
}