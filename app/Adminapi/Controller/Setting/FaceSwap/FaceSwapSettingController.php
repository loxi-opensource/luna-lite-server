<?php

namespace App\Adminapi\Controller\Setting\FaceSwap;

use App\Adminapi\Controller\BaseAdminController;
use App\Adminapi\Logic\Setting\FaceSwap\FaceSwapSettingLogic;
use App\Adminapi\Validate\Setting\FaceSwapSettingValidate;

class FaceSwapSettingController extends BaseAdminController
{
    /**
     * @notes 获取换脸算法服务配置
     */
    public function getFaceSwapKey()
    {
        $result = FaceSwapSettingLogic::getFaceSwapKey();
        return $this->data($result);
    }

    /**
     * @notes 获取换脸算法服务配置
     */
    public function setFaceSwapKey()
    {
        $params = (new FaceSwapSettingValidate())->post()->goCheck('setFaceSwapKey');
        FaceSwapSettingLogic::setFaceSwapKey($params);
        return $this->success('设置成功', [], 1, 1);
    }
}
