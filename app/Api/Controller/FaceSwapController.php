<?php

namespace App\Api\Controller;

use App\Api\Logic\FaceSwapLogic;
use App\Common\Model\User\User;
use App\Common\Types\Swap\GenerateParams;
use Illuminate\Http\Request;

class FaceSwapController extends BaseApiController
{
    // 生成AI换脸结果
    public function createSwap(Request $request)
    {
        try {
            $params = new GenerateParams(
                $request->post('target_image'),
                $request->post('user_image'),
                $request->post('face_mapping')
            );
        } catch (\Exception $e) {
            return $this->fail($e->getMessage());
        }

        $result = FaceSwapLogic::createSwap(User::query()->findOrFail($this->getUserId()), $params);
        if ($result === false) {
            $errMsg = FaceSwapLogic::getError();
            return $this->fail($errMsg);
        }

        return $this->success("success", compact('result'));
    }

    // 上传用户图，制作数字分身
    public function createAvatar()
    {
        $result = FaceSwapLogic::createAvatar($this->getUserId());
        if ($result === false) {
            $errMsg = FaceSwapLogic::getError();
            return $this->fail($errMsg);
        }

        return $this->success("success", $result);
    }

    // 用户作图记录
    public function userRecords(Request $request)
    {
        $lists = FaceSwapLogic::userRecords($this->getUserId(), $request->get('per_page', 10));
        return $this->success("success", compact('lists'));
    }

}
