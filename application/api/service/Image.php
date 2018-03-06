<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/12/6
 * Time: 14:19
 */

namespace app\api\service;


use app\lib\exception\ImageException;

class Image
{
    public static function uploadAgentImages()
    {
        // 获取表单上传文件
        try {
            static $path = [];
            $files = request()->file('image');

            foreach ($files as $file) {
                // 移动到框架应用根目录/public/uploads/ 目录下
                $info = $file->validate(['ext' => 'jpeg,jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'uploads' . DS . 'agents');
                if (!$info) {
                    throw new ImageException([
                        'msg' => $file->getError()
                    ]);
                }
                // 成功上传后 获取上传信息
                $path[] = $info->getSaveName();
            }
            return [
                "code" => 201,
                "path" => $path
            ];
        } catch (\Exception $e) {
            throw new ImageException([
                'msg' => '请不要上传非法文件'
            ]);
        }
    }

    public static function uploadStaffImages()
    {
        // 获取表单上传文件
        try {
            static $path = [];
            $files = request()->file('image');

            foreach ($files as $file) {
                // 移动到框架应用根目录/public/uploads/ 目录下
                $info = $file->validate(['ext' => 'jpeg,jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'uploads' . DS . 'staff');
                if (!$info) {
                    throw new ImageException([
                        'msg' => $file->getError()
                    ]);
                }
                // 成功上传后 获取上传信息
                $path[] = $info->getSaveName();
            }
            return [
                'code' => 201,
                'path' => $path
            ];
        } catch (\Exception $e) {
            throw new ImageException([
                'msg' => '请不要上传非法文件'
            ]);
        }
    }
}