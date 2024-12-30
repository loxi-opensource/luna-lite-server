<?php

namespace App\Common\Model;

use App\Common\Service\FileService;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;

class DigitalAvatar extends BaseModel
{
    protected $table = 'digital_avatar';

    protected function userImage(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => trim($value) ? FileService::getFileUrl($value) : '',
            set: fn(string $value) => trim($value) ? FileService::setFileUrl($value) : ''
        );
    }

    protected function faceImage(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => trim($value) ? FileService::getFileUrl($value) : '',
            set: fn(string $value) => trim($value) ? FileService::setFileUrl($value) : ''
        );
    }

}
