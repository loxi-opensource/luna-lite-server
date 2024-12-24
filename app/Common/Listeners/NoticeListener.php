<?php

namespace App\Common\Listeners;

use App\Common\Events\NoticeEvent;
use App\Common\Logic\NoticeLogic;
use Illuminate\Support\Facades\Log;

// 确保路径正确
class NoticeListener
{
    public function handle(NoticeEvent $event): void
    {
        try {
            $params = $event->params;

            if (empty($params['scene_id'])) {
                throw new \Exception('场景ID不能为空');
            }

            // 根据不同的场景发送通知
            $result = NoticeLogic::noticeByScene($params);
            if (false === $result) {
                throw new \Exception(NoticeLogic::getError());
            }

        } catch (\Exception $e) {
            Log::error('通知发送失败: ' . $e->getMessage());
            throw $e;
        }
    }
}
