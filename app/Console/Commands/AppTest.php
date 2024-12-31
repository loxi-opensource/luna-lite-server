<?php

namespace App\Console\Commands;

use App\Api\Logic\FaceSwapLogic;
use App\Common\Service\FaceSwap\FaceSwapService;
use App\Common\Types\Swap\GenerateParams;
use App\Common\Model\User\User;
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
        $userFileUrl = 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/luna/userFile/eaa49139237d4c4a89b06bd43dc187b0.webp';

        $params = [
            'target_image' => $targetFileUrl,
            'user_image' => $userFileUrl,
            'face_mapping' => [
                "T#0" => "U#0"
            ],
        ];
        $this->info(json_encode($params));
        return;
        $genParams = new GenerateParams($params['target_image'], $params['user_image'], $params['face_mapping']);
//        $res = (new FaceSwapService())->swapFaces($genParams);
        $res = FaceSwapLogic::createSwap(User::query()->find(1), $genParams);
        if (!$res) {
            $errMsg = FaceSwapLogic::getError();
            $this->error($errMsg);
            return;
        }
        dd($res);
    }

}
