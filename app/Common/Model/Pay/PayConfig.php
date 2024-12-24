<?php

namespace App\Common\Model\Pay;

use App\Common\Enum\PayEnum;
use App\Common\Model\BaseModel;
use App\Common\Service\FileService;


class PayConfig extends BaseModel
{
    protected $table = 'dev_pay_config';

    public $casts = [
        'config' => 'array',
    ];

    protected $appends = ['pay_way_name'];

    public $timestamps = false;

    /**
     * @notes 支付图标获取器 - 路径添加域名
     * @param $value
     * @return string
     */
    public function getIconAttribute($value)
    {
        return empty($value) ? '' : FileService::getFileUrl($value);
    }

    /**
     * @notes 支付方式名称获取器
     * @param $value
     * @param $data
     * @return string|string[]
     */
    public function getPayWayNameAttribute($value)
    {
        return PayEnum::getPayDesc($this->attributes['pay_way']);
    }
}
