<?php

namespace App\Adminapi\Logic\Channel;

use App\Common\Enum\OfficialAccountEnum;
use App\Common\Enum\YesNoEnum;
use App\Common\Logic\BaseLogic;
use App\Common\Model\Channel\OfficialAccountReply;
use App\Common\Service\Wechat\WechatOaService;

/**
 * 微信公众号回复逻辑层
 */
class OfficialAccountReplyLogic extends BaseLogic
{
    /**
     * @notes 添加回复(关注/关键词/默认)
     */
    public static function add(array $params)
    {
        try {
            // 关键字回复排序值须大于或等于0
            if ($params['reply_type'] == OfficialAccountEnum::REPLY_TYPE_KEYWORD && $params['sort'] < 0) {
                throw new \Exception('排序值须大于或等于0');
            }
            if ($params['reply_type'] != OfficialAccountEnum::REPLY_TYPE_KEYWORD && $params['status']) {
                // 非关键词回复只能有一条记录处于启用状态
                OfficialAccountReply::where('reply_type', $params['reply_type'])
                    ->update(['status' => YesNoEnum::NO]);
            }

            // Likeadmin BUG：数据库并没有这个字段
            if (isset($params['reply_num'])) {
                unset($params['reply_num']);
            }
            if (isset($params['id'])) {
                unset($params['id']);
            }

            OfficialAccountReply::create($params);
            return true;
        } catch (\Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }

    /**
     * @notes 查看回复详情
     */
    public static function detail(array $params)
    {
        return OfficialAccountReply::select([
            'id', 'name', 'keyword', 'reply_type', 'matching_type',
            'content_type', 'content', 'status', 'sort',
            'reply_type as reply_type_desc',
            'matching_type as matching_type_desc',
            'content_type as content_type_desc',
            'status as status_desc'
        ])->findOrFail($params['id'])->toArray();
    }

    /**
     * @notes 编辑回复(关注/关键词/默认)
     */
    public static function edit(array $params)
    {
        try {
            // 关键字回复排序值须大于或等于0
            if ($params['reply_type'] == OfficialAccountEnum::REPLY_TYPE_KEYWORD && $params['sort'] < 0) {
                throw new \Exception('排序值须大于或等于0');
            }
            if ($params['reply_type'] != OfficialAccountEnum::REPLY_TYPE_KEYWORD && $params['status']) {
                // 非关键词回复只能有一条记录处于启用状态
                OfficialAccountReply::where('reply_type', $params['reply_type'])
                    ->update(['status' => YesNoEnum::NO]);
            }
            // Likeadmin BUG：数据库并没有这个字段
            if (isset($params['reply_num'])) {
                unset($params['reply_num']);
            }
            OfficialAccountReply::where('id', $params['id'])->update($params);
            return true;
        } catch (\Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }

    /**
     * @notes 删除回复(关注/关键词/默认)
     */
    public static function delete(array $params)
    {
        OfficialAccountReply::destroy($params['id']);
    }

    /**
     * @notes 更新排序
     */
    public static function sort(array $params)
    {
        OfficialAccountReply::where('id', $params['id'])->update(['sort' => $params['new_sort']]);
    }

    /**
     * @notes 更新状态
     */
    public static function status(array $params)
    {
        $reply = OfficialAccountReply::findOrFail($params['id']);
        $reply->status = !$reply->status;
        $reply->save();
    }

    /**
     * @notes 微信公众号回调
     */
    public static function index()
    {
        $server = (new WechatOaService())->getServer();

        // 事件
        $server->addMessageListener(OfficialAccountEnum::MSG_TYPE_EVENT, function ($message, \Closure $next) {
            switch ($message['Event']) {
                case OfficialAccountEnum::EVENT_SUBSCRIBE: // 关注事件
                    $replyContent = OfficialAccountReply::where([
                        'reply_type' => OfficialAccountEnum::REPLY_TYPE_FOLLOW,
                        'status' => YesNoEnum::YES
                    ])
                        ->value('content');

                    if ($replyContent) {
                        return $replyContent;
                    }
                    break;
            }
            return $next($message);
        });

        // 文本
        $server->addMessageListener(OfficialAccountEnum::MSG_TYPE_TEXT, function ($message, \Closure $next) {
            $replyList = OfficialAccountReply::where([
                'reply_type' => OfficialAccountEnum::REPLY_TYPE_KEYWORD,
                'status' => YesNoEnum::YES
            ])->orderBy('sort', 'asc')->get();

            $replyContent = '';
            foreach ($replyList as $reply) {
                switch ($reply['matching_type']) {
                    case OfficialAccountEnum::MATCHING_TYPE_FULL:
                        $reply['keyword'] === $message['Content'] && $replyContent = $reply['content'];
                        break;
                    case OfficialAccountEnum::MATCHING_TYPE_FUZZY:
                        stripos($message['Content'], $reply['keyword']) !== false && $replyContent = $reply['content'];
                        break;
                }
                if ($replyContent) {
                    break; // 得到回复文本，中止循环
                }
            }
            //消息回复为空的话，找默认回复
            if (empty($replyContent)) {
                $replyContent = static::getDefaultReply();
            }
            if ($replyContent) {
                return $replyContent;
            }
            return $next($message);
        });

        return $server->serve();
    }

    /**
     * @notes 默认回复信息
     */
    public static function getDefaultReply()
    {
        return OfficialAccountReply::where([
            'reply_type' => OfficialAccountEnum::REPLY_TYPE_DEFAULT,
            'status' => YesNoEnum::YES
        ])->value('content');
    }
}
