<?php

namespace App\Common\Model;

use App\Common\Service\FileService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

/**
 * 基础模型
 */
class BaseModel extends Model
{
    public $guarded = [];

    public $casts = [
        'create_time' => 'datetime:Y-m-d H:i:s',
        'update_time' => 'datetime:Y-m-d H:i:s',
    ];

    protected $dateFormat = 'U';

    public function getCreatedAtColumn()
    {
        return 'create_time';
    }

    public function getUpdatedAtColumn()
    {
        return 'update_time';
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->timezone(Config::get('app.timezone'))->format('Y-m-d H:i:s');
    }

    protected function getDeletedAtColumn()
    {
        return 'delete_time';
    }

    /**
     * @notes 公共处理图片,补全路径
     * @param $value
     * @return string
     */
    public function getImageAttribute($value)
    {
        return trim($value) ? FileService::getFileUrl($value) : '';
    }

    /**
     * @notes 公共图片处理,去除图片域名
     * @param $value
     */
    public function setImageAttribute($value)
    {
        return trim($value) ? FileService::setFileUrl($value) : '';
    }

}
