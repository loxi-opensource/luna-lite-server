<?php

namespace App\Adminapi\Logic\Setting\FaceSwap;

use App\Common\Logic\BaseLogic;
use App\Common\Service\ConfigService;

class FaceSwapSettingLogic extends BaseLogic
{
    /**
     * @notes 获取换脸算法服务配置
     */
    public static function getFaceSwapKey()
    {
        return [
            'ak' => ConfigService::get('faceSwapKey', 'ak'),
            'sk' => ConfigService::get('faceSwapKey', 'sk'),
        ];
    }

    /**
     * @notes 设置换脸算法服务配置
     */
    public static function setFaceSwapKey(array $params)
    {
        ConfigService::set('faceSwapKey', 'ak', $params['ak']);
        ConfigService::set('faceSwapKey', 'sk', $params['sk']);
    }

}
