<?php

namespace App\Common\Model;

use App\Common\Service\FileService;
use Illuminate\Database\Eloquent\Casts\Attribute;
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

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => trim($value) ? FileService::getFileUrl($value) : '',
            set: fn(string $value) => trim($value) ? FileService::setFileUrl($value) : ''
        );
    }
}
