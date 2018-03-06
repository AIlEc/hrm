<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/11/29
 * Time: 10:18
 */

namespace app\api\model;


use think\Paginator;

class Employment extends Base
{
    protected $autoWriteTimestamp = true;
    protected $hidden = ['pivot.id', 'pivot.employment_id', 'pivot.staff_id', 'update_time', 'delete_time'];

    public function detailEmployment()
    {
        return $this->hasOne('EmploymentDetail', 'id', 'detail_id');
    }

    public function staffs()
    {
        return $this->belongsToMany('Staff', 'staff_employment');
    }

    public function inJobStaffs()
    {
        return $this->hasMany('Staff','employment_id');
    }

    public function getIsLooperAttr($value)
    {
        $isLooper = [0 => '否', 1 => '是'];
        return $isLooper[$value];
    }

    public function getStatusAttr($value)
    {
        $status = [0 => '停用', 1 => '启用'];
        return $status[$value];
    }

    public function getWelfareAttr($value)
    {
        if ($value) {
            $data = json_decode($value);
            $result = implode(',', $data);
            return $result;
        }
        return '';
    }
	
    public static function getAllEmployments($page, $limit)
    {
        $result = self::withCount('inJobStaffs')
            ->order('create_time asc')
            ->paginate($limit, false, ['page' => $page]);
        return $result;
    }

    public static function getEmploymentInIDS($ids)
    {
        $result = self::where('id', 'in', $ids)
            ->select();
        return $result;
    }

    public static function getEmploymentDetail($id)
    {
        $employment = self::with('detailEmployment')
            ->find($id);
        return $employment;
    }
}