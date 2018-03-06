<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/12/7
 * Time: 15:04
 */

namespace app\api\model;


use think\Model;

class Staff extends Model
{
    protected $autoWriteTimestamp = true;
    protected $hidden = ['user_id', 'employment_id', 'update_time', 'create_time', 'delete_time', 'auditor_id',
        'pivot.staff_id','pivot.id','pivot.employment_id'];

    public function employment()
    {
        return $this->belongsToMany('Employment');
    }

    public function getSexAttr($value)
    {
        $data = [0 => '女', '1' => '男'];
        return $data[$value];
    }
    
}