<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/11/27
 * Time: 17:44
 */

namespace app\api\validate;


use app\lib\exception\ParameterException;
use think\Exception;
use think\Request;
use think\Validate;

class Base extends Validate
{
    /**
     * 检测所有客户端发来的参数是否符合验证规则
     * 自定义验证方法可以直接使用
     * @throws Exception
     */
    public function goCheck()
    {
        //必须设置content-type:application/json
        $request = Request::instance();
        $params = $request->param();

        if (!$this->batch()->check($params)) {
            throw new ParameterException([
                'msg' => is_array($this->error) ? implode(';', $this->error) : $this->error,
            ]);
        }
        return true;
    }

    /**
     * @param $value
     * @param string $rule
     * @param string $data
     * @param string $field
     * @return bool|string
     * 判断正整数
     */
    protected function isPositiveInteger($value, $rule = '', $data = '', $field = '')
    {
        if (is_numeric($value) && is_int($value + 0) && ($value + 0) > 0) {
            return true;
        }
        return $field . '必须是正整数';
    }

    protected function isNotEmpty($value, $rule = '', $data = '', $field = '')
    {
        if (empty($value)) {
            return $field . '不允许为空';
        } else {
            return true;
        }
    }

    //验证是否为手机号
    protected function isMobile($value, $rule = '', $data = '', $field = '')
    {
        $rule = '^1(3|4|5|7|8)[0-9]\d{8}$^';
        $result = preg_match($rule, $value);
        if ($result) {
            return true;
        } else {
            return $field . '必须为手机号';
        }
    }

    //验证身份证号
    protected function isIDCard($value, $rule = '', $data = '', $field = '')
    {
        ///^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{4}$/
        $preg_card = '/^\d{6}(18|19|20)?\d{2}(0[1-9]|1[012])(0[1-9]|[12]\d|3[01])\d{3}(\d|[xX])$/';
        if (preg_match($preg_card, $value)) {
            return true;
        } else {
            return $field . '必须为身份证号';
        }
    }

    //验证银行卡号
    protected function isBankCard($value, $rule = '', $data = '', $field = '')
    {
        $preg_bankcard = '/([\d]{4})([\d]{4})([\d]{4})([\d]{4})([\d]{0,})?/';
        if (preg_match($preg_bankcard, $value)) {
            return true;
        } else {
            return $field . '必须为银行卡号';
        }
    }

    //验证只有中文汉字
    protected function isOnlyChinese($value, $rule = '', $data = '', $field = '')
    {
        $rule = '/^[\x{4e00}-\x{9fa5}]+$/u';
        if (preg_match($rule, $value)) {
            return true;
        } else {
            return $field . '只能由汉字组成';
        }
    }
}