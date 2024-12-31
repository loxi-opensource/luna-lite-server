<?php

namespace App\Console\Commands;

use App\Common\Model\SwapTemplate;
use App\Common\Model\SwapTemplateGroup;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Overtrue\Pinyin\Pinyin;
use Symfony\Component\Finder\SplFileInfo;

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
        $pinyinNameMapTemplateGroup = SwapTemplateGroup::query()->select([
            'id',
            'name',
        ])->get()->map(function (SwapTemplateGroup $group) {
            $group->pinyin = self::getPinyin($group->name);
            return $group;
        })->keyBy('pinyin')->toArray();

        $localDir = public_path('resource/image/faceswap');
        // 遍历文件夹
        $files = File::allFiles($localDir);
        collect($files)->each(function (SplFileInfo $file) use ($pinyinNameMapTemplateGroup) {
            $relativePath = str_replace(public_path(), '', $file->getPathname());
            $relativePath = str_replace('\\', '/', $relativePath);
            $relativePath = ltrim($relativePath, '/');
            // 查找出匹配拼音的模板组。拼音正则匹配 luna_之后的一个词
            $pinyin = preg_match('/luna_(\w+)/', $relativePath, $matches) ? $matches[1] : '';
            $group = $pinyinNameMapTemplateGroup[$pinyin] ?? null;
            if ($group) {
                $this->info("Matched: {$relativePath} => {$group['name']}");

                if (SwapTemplate::query()->where('group_id', $group['id'])->where('target_image', $relativePath)->exists()) {
                    return;
                } else {
                    SwapTemplate::query()->create([
                        'group_id' => $group['id'],
                        'name' => $group['name'],
                        'status' => 1,
                        'target_image' => $relativePath,
                    ]);
                }
            } else {
                $this->warn("Not Matched: {$relativePath}");
            }
        });
    }

    static function getPinyin($chinese)
    {
        $pinyin = Pinyin::sentence($chinese, 'none');
        return $pinyin->join('');
    }


}
