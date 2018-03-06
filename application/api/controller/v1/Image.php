<?php
/**
 * Created by PhpStorm.
 * User: August.Fang
 * Date: 2017/12/6
 * Time: 14:10
 */

namespace app\api\controller\v1;


use app\api\controller\Base;
use app\api\service\Image as ImageService;

class Image extends Base
{
    /**
     * @api 'image/upload_agent'
     * @post 'post'
     * @return array
     * @throws \app\lib\exception\ImageException
     */
    public function uploadAgentImages()
    {
        $result = ImageService::uploadAgentImages();
        return $result;
    }

    /**
     * @api 'image/upload_staff'
     * @post 'image'
     * @return array
     * @throws \app\lib\exception\ImageException
     */
    public function uploadStaffImages()
    {
        $result = ImageService::uploadStaffImages();
        return $result;
    }
}