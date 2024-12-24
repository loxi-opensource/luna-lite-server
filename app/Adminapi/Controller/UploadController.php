<?php

namespace App\Adminapi\Controller;

use App\Common\Service\UploadService;
use Exception;
use Illuminate\Http\Request;

/**
 * 上传文件
 */
class UploadController extends BaseAdminController
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
            'file.mimes' => '请上传正确的图片文件类型'
        ]);

        try {
            $cid = $this->request->post('cid', 0);
            $result = UploadService::image(intval($cid));
            return $this->success('上传成功', $result);
        } catch (Exception $e) {
            return $this->fail($e->getMessage());
        }
    }

    /**
     * @notes 上传视频
     */
    public function video(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:' . implode(',', config('project.file_video'))
        ], [
            'file.required' => '请上传文件',
            'file.file' => '请上传文件',
            'file.mimes' => '请上传正确的视频文件类型'
        ]);

        try {
            $cid = $this->request->post('cid', 0);
            $result = UploadService::video(intval($cid));
            return $this->success('上传成功', $result);
        } catch (Exception $e) {
            return $this->fail($e->getMessage());
        }
    }

    /**
     * @notes 上传文件
     */
    public function file(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:' . implode(',', config('project.file_file')),
        ], [
            'file.required' => '请上传文件',
            'file.file' => '请上传文件',
            'file.mimes' => '请上传正确的文件类型'
        ]);

        try {
            $cid = $this->request->post('cid', 0);
            $result = UploadService::file(intval($cid));
            return $this->success('上传成功', $result);
        } catch (Exception $e) {
            return $this->fail($e->getMessage());
        }
    }

}
