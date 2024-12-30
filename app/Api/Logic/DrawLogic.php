<?php

namespace App\Api\Logic;

use App\Common\Enum\User\AccountLogEnum;
use App\Common\Model\User;
use App\Common\Service\ConfigService;
use App\Services\Storage\Driver as StorageDriver;
use Illuminate\Support\Facades\Log;

/**
 * 作图逻辑
 */
class DrawLogic
{
    /**
     * @notes 校验能否作图
     */
    public static function checkAbleDraw($userId, $needBalance)
    {
        $user = User::find($userId);

        if (!$user || $user->balance_draw < $needBalance) {
            return '余额不足';
        }
        return true;
    }

    /**
     * @notes 作图余额处理
     */
    public static function drawBalanceHandle($userId, $usedToken, $changeType)
    {
        // 用户信息
        $user = User::findOrEmpty($userId);

        // $action 变动类型 $totalDraw 累计作图消费 $balanceDraw 作图余额
        if (in_array($changeType, AccountLogEnum::DRAW_INC)) {
            $action = AccountLogEnum::INC;
            $totalDraw = max(0, $user->total_draw - $usedToken);
            $balanceDraw = $user->balance_draw + $usedToken;
        } else {
            $action = AccountLogEnum::DEC;
            $totalDraw = $user->total_draw + $usedToken;
            $balanceDraw = $user->balance_draw - $usedToken;
        }

        $user->balance_draw = $balanceDraw;
        $user->total_draw = $totalDraw;
        $user->save();

        // 记录账户流水
        AccountLogLogic::add($userId, $changeType, $action, $usedToken);
    }

    /**
     * @notes 下载图片
     */
    public static function downloadImage($downloadUrl): string
    {
        if (empty($downloadUrl)) {
            return '';
        }

        try {
            // 存储引擎
            $config = [
                'default' => ConfigService::get('storage', 'default', 'local'),
                'engine' => ConfigService::get('storage')
            ];

            // 文件名称
            $fileName = md5($downloadUrl) . '.png';

            // 第三方存储
            if ($config['default'] == 'local') {
                // 下载到本地,如果存储为oss时,生成缩略图后删除
                $localPath = download_file($downloadUrl, 'uploads/draw/' . date('Ymd') . '/', $fileName);
            } else {
                $localPath = 'uploads/draw/' . date('Ymd') . '/' . $fileName;
                $driver = new StorageDriver($config);
                if (!$driver->fetch($downloadUrl, $localPath)) {
                    throw new \Exception('作图保存失败:' . $driver->getError());
                }
            }

            return $localPath;

        } catch (\Exception $e) {
            Log::error('作图结果图片下载失败' . $e->getMessage(), [
                'line' => $e->getLine()
            ]);
            return '';
        }
    }
}
