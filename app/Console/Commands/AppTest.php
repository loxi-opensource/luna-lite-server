<?php

namespace App\Console\Commands;

use App\Common\Service\FaceSwap\FaceSwapService;
use Illuminate\Console\Command;

class AppTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '开发测试';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $targetFileUrl = 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/libraries/%E4%B8%87%E4%BA%8B%E5%85%B4%E9%BE%99/%E5%8F%A4%E9%A3%8E_%E5%A5%B3/cb54a0a0ea89a4692eb57a4c4eba1a02.webp';

        // 多人
        $targetFileUrl = 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/libraries/oneToOne/d635ec7e3af94057b154a84236d04958.webp';

        $userFileUrl = 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/luna/userFile/eaa49139237d4c4a89b06bd43dc187b0.webp';

        // 无人脸
//        $userFileUrl = 'https://51meibainian.oss-cn-beijing.aliyuncs.com/img604swipe0.jpeg';

        try {
            $res = (new FaceSwapService())->swapFaces($targetFileUrl, $userFileUrl);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
            return;
        }
        dd($res);
    }

}
