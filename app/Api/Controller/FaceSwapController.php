<?php

namespace App\Api\Controller;

use App\Api\Logic\FaceSwapLogic;
use App\Api\Validate\FaceSwapValidate;
use App\Common\Model\DigitalAvatar;
use App\Common\Model\SwapTemplate;
use App\Common\Model\User\User;
use App\Common\Types\Swap\GenerateParams;
use Illuminate\Http\Request;

class FaceSwapController extends BaseApiController
{
    // 生成AI换脸结果
    public function createSwap(Request $request)
    {
        $params = (new FaceSwapValidate())->post()->goCheck('createSwap');
        $template = SwapTemplate::query()->with('templateGroup')->find($params['template_id']);
        $avatar = DigitalAvatar::query()->find($params['avatar_id']);

        $targetImageFaceIndex = '0';
        $faceMapping = [
            'T#' . $targetImageFaceIndex => 'U#' . $avatar->face_id,
        ];

        try {
            // 组装换脸参数
            $params = new GenerateParams(
                $template->target_image,
                $avatar->user_image,
                $faceMapping,
            );
        } catch (\Exception $e) {
            return $this->fail($e->getMessage());
        }

        $user = User::query()->findOrFail($this->getUserId());
        $result = FaceSwapLogic::createSwap($user, $params, $template, $avatar);
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

    // 删除用户作图记录
    public function removeRecord(Request $request)
    {
        $params = (new FaceSwapValidate())->post()->goCheck('removeRecord');
        $result = FaceSwapLogic::removeRecord($this->getUserId(), $params['id']);
        if ($result === false) {
            $errMsg = FaceSwapLogic::getError();
            return $this->fail($errMsg);
        }

        return $this->success("success");
    }
}
