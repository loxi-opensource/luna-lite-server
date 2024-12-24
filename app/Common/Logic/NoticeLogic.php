<?php

namespace App\Common\Logic;

use App\Common\Enum\Notice\NoticeEnum;
use App\Common\Enum\YesNoEnum;
use App\Common\Model\Notice\NoticeRecord;
use App\Common\Model\Notice\NoticeSetting;
use App\Common\Model\User\User;
use App\Common\Service\Sms\SmsMessageService;

/**
 * 通知逻辑层
 */
class NoticeLogic extends BaseLogic
{

    /**
     * @notes 根据场景发送短信
     * @param $params
     * @return bool
     */
    public static function noticeByScene($params)
    {
        try {
            $noticeSetting = NoticeSetting::query()->where('scene_id', $params['scene_id'])->first();
            if (empty($noticeSetting)) {
                throw new \Exception('找不到对应场景的配置');
            }
            // 合并额外参数
            $params = self::mergeParams($params);
            $res = false;
            self::setError('发送通知失败');

            // 短信通知
            if (isset($noticeSetting['sms_notice']['status']) && $noticeSetting['sms_notice']['status'] == YesNoEnum::YES) {
                $res = (new SmsMessageService())->send($params);
            }

            return $res;
        } catch (\Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }


    /**
     * @notes 整理参数
     * @param $params
     * @return array
     */
    public static function mergeParams($params)
    {
        // 用户相关
        if (!empty($params['params']['user_id'])) {
            $user = User::find($params['params']['user_id']);
            $params['params']['nickname'] = $user['nickname'];
            $params['params']['user_name'] = $user['nickname'];
            $params['params']['user_sn'] = $user['sn'];
            $params['params']['mobile'] = $params['params']['mobile'] ?? $user['mobile'];
        }

        // 跳转路径
        $jumpPath = self::getPathByScene($params['scene_id'], $params['params']['order_id'] ?? 0);
        $params['url'] = $jumpPath['url'];
        $params['page'] = $jumpPath['page'];

        return $params;
    }


    /**
     * @notes 根据场景获取跳转链接
     * @param $sceneId
     * @param $extraId
     * @return string[]
     */
    public static function getPathByScene($sceneId, $extraId)
    {
        // 小程序主页路径
        $page = '/pages/index/index';
        // 公众号主页路径
        $url = '/mobile/pages/index/index';
        return [
            'url' => $url,
            'page' => $page,
        ];
    }


    /**
     * @notes 替换消息内容中的变量占位符
     * @param $content
     * @param $params
     * @return array|mixed|string|string[]
     */
    public static function contentFormat($content, $params)
    {
        foreach ($params['params'] as $k => $v) {
            $search = '{' . $k . '}';
            $content = str_replace($search, $v, $content);
        }
        return $content;
    }


    /**
     * @notes 添加通知记录
     * @param $params
     * @param $noticeSetting
     * @param $sendType
     * @param $content
     * @param string $extra
     */
    public static function addNotice($params, $noticeSetting, $sendType, $content, $extra = '')
    {
        return NoticeRecord::query()->create([
            'user_id' => $params['params']['user_id'] ?? 0,
            'title' => self::getTitleByScene($sendType, $noticeSetting),
            'content' => $content,
            'scene_id' => $noticeSetting['scene_id'],
            'read' => YesNoEnum::NO,
            'recipient' => $noticeSetting['recipient'],
            'send_type' => $sendType,
            'notice_type' => $noticeSetting['type'],
            'extra' => $extra,
        ]);
    }


    /**
     * @notes 通知记录标题
     * @param $sendType
     * @param $noticeSetting
     * @return string
     */
    public static function getTitleByScene($sendType, $noticeSetting)
    {
        switch ($sendType) {
            case NoticeEnum::SMS:
                $title = '';
                break;
            case NoticeEnum::OA:
                $title = $noticeSetting['oa_notice']['name'] ?? '';
                break;
            case NoticeEnum::MNP:
                $title = $noticeSetting['mnp_notice']['name'] ?? '';
                break;
            default:
                $title = '';
        }
        return $title;
    }

}
