<?php

namespace App\Api\Controller;

use App\Common\Enum\FileEnum;
use App\Common\Service\UploadService;
use Exception;
use Illuminate\Http\Request;

/** 上传文件
 */
class UploadController extends BaseApiController
{
    /**
     * @notes 上传图片
     */
    public function image(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:' . implode(',', config('project.file_image'))
        ], [
            'file.required' => '请上传文件',
            'file.file' => '请上传文件',
            'file.mimes' => '请上传正确的文件类型'
        ]);

        try {
            $result = UploadService::image(0, $this->getUserId(), FileEnum::SOURCE_USER);
            return $this->success('上传成功', $result);
        } catch (Exception $e) {
            return $this->fail($e->getMessage());
        }
    }

}
