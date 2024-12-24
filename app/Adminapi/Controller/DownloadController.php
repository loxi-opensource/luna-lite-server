<?php

namespace App\Adminapi\Controller;

use App\Common\Cache\ExportCache;
use App\Common\Service\JsonService;

class DownloadController extends BaseAdminController
{

    public array $notNeedLogin = ['export'];

    /**
     * @notes 导出文件
     */
    public function export()
    {
        //获取文件缓存的key
        $fileKey = request()->get('file');

        //通过文件缓存的key获取文件储存的路径
        $exportCache = new ExportCache();
        $fileInfo = $exportCache->getFile($fileKey);

        if (empty($fileInfo)) {
            return JsonService::fail('下载文件不存在');
        }

        //下载前删除缓存
        $exportCache->delete($fileKey);

        // 下载文件
        return response()->download($fileInfo['src'] . $fileInfo['name']);
    }

}
