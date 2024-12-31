<?php

namespace App\Console\Commands;

use App\Common\Service\UploadService;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class SyncResourceToOss extends Command
{
    use AddTimestampBeforeOutput;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-resource-to-oss';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '同步静态资源到OSS';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->line('开始执行');

        $toSyncPublicDirList = [
            'resource/image/adminapi', // likeadmin静态资源
            'resource/image/faceswap', // AI换脸示例模板图片
        ];

        foreach ($toSyncPublicDirList as $toSyncPublicDir) {
            $localDir = public_path($toSyncPublicDir);
            $this->syncOneDir($localDir, $toSyncPublicDir);
            $this->line('--------------------------------');
        }

        $this->line('执行完毕');
    }

    private function syncOneDir($localDir, $tagetDir)
    {
        $this->line('目录' . $tagetDir . '开始执行');
        $this->warn('可能需要几分钟，请耐心等待...');

        try {
            $res = UploadService::syncLocalDirectory($localDir, $tagetDir);
        } catch (\Exception $e) {
            $this->fail('同步失败：' . $e->getMessage());
            return;
        }

        $successList = Arr::get($res, 'succeededList', []);
        $failList = Arr::get($res, 'failedList', []);

        if (count($successList) > 0) {
            $this->info('成功同步' . count($successList) . '个文件！');
        }

        if (count($failList) > 0) {
            foreach ($failList as $error) {
                $this->warn($error);
            }
            if (count($successList) == 0) {
                $this->fail('全部同步失败：共' . count($failList) . '个文件');
            } else {
                $this->error('同步失败' . count($failList) . '个文件');
            }
        }

        $this->line('目录' . $tagetDir . '执行完毕');
    }
}
