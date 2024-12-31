<?php

namespace App\Console\Commands;

use App\Api\Logic\FaceSwapLogic;
use App\Common\Service\FaceSwap\FaceSwapService;
use App\Common\Service\UploadService;
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
        $localDir = public_path('freddytest');
        $res = UploadService::syncLocalDirectory($localDir, 'test4/');
        dd($res);
    }

}
