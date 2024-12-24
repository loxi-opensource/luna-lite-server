<?php

namespace App\Common\Model\Notice;

use App\Common\Enum\DefaultEnum;
use App\Common\Enum\Notice\NoticeEnum;
use App\Common\Model\BaseModel;

class NoticeSetting extends BaseModel
{
    protected $table = 'notice_setting';

    protected $appends = [
        'sms_status_desc',
        'type_desc',
    ];

    /**
     * 短信通知状态
     *
     * @param mixed $value
     * @return string
     */
    public function getSmsStatusDescAttribute($value): string
    {
        if ($this->attributes['sms_notice']) {
            $smsText = json_decode($this->attributes['sms_notice'], true);
            return DefaultEnum::getEnableDesc($smsText['status']);
        }
        return '停用';
    }

    /**
     * 通知类型描述
     *
     * @param mixed $value
     * @return string
     */
    public function getTypeDescAttribute($value): string
    {
        return NoticeEnum::getTypeDesc($this->attributes['type']);
    }

    /**
     * 接收者描述获取器
     *
     * @param mixed $value
     * @return string
     */
    public function getRecipientDescAttribute($value): string
    {
        $desc = [
            1 => '买家',
            2 => '卖家',
        ];
        return $desc[$value] ?? '';
    }

    /**
     * 系统通知获取器
     *
     * @param mixed $value
     * @return array
     */
    public function getSystemNoticeAttribute($value): array
    {
        return empty($value) ? [] : json_decode($value, true);
    }

    /**
     * 短信通知获取器
     *
     * @param mixed $value
     * @return array
     */
    public function getSmsNoticeAttribute($value): array
    {
        return empty($value) ? [] : json_decode($value, true);
    }

    /**
     * 公众号通知获取器
     *
     * @param mixed $value
     * @return array
     */
    public function getOaNoticeAttribute($value): array
    {
        return empty($value) ? [] : json_decode($value, true);
    }

    /**
     * 小程序通知获取器
     *
     * @param mixed $value
     * @return array
     */
    public function getMnpNoticeAttribute($value): array
    {
        return empty($value) ? [] : json_decode($value, true);
    }
}
