<?php

namespace App\Api\Logic;

use App\Common\Enum\Notice\NoticeEnum;
use App\Common\Events\NoticeEvent;
use App\Common\Logic\BaseLogic;

/**
 * 短信逻辑
 */
class SmsLogic extends BaseLogic
{

    /**
     * @notes 发送验证码
     * @param $params
     * @return false|mixed
     */
    public static function sendCode($params)
    {
        try {
            $scene = NoticeEnum::getSceneByTag($params['scene']);
            if (empty($scene)) {
                throw new \Exception('场景值异常');
            }

            event(new NoticeEvent([
                'scene_id' => $scene,
                'params' => [
                    'mobile' => $params['mobile'],
                    'code' => mt_rand(1000, 9999),
                ]
            ]));

            return true;
        } catch (\Exception $e) {
            self::$error = $e->getMessage();
            return false;
        }
    }

}
