<?php
// +----------------------------------------------------------------------
// | Luna-Swapping开源AI换脸项目Lite版本（基于likeadmin_laravel框架）
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | Github开源仓库：https://github.com/loxi-opensource/luna-lite-server
// | 全栈开发框架：https://github.com/1nFrastr/likeadmin_laravel
// | 独立开发者博客：https://www.sodair.top
// +----------------------------------------------------------------------
// | author: 1nFrastr x likeadminTeam
// +----------------------------------------------------------------------

namespace App\Common\Model;


use App\Common\Model\BaseModel;
use App\Common\Service\FileService;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * SwapRecord模型
 * Class SwapRecord
 * @package App\Common\Model
 */
class SwapRecord extends BaseModel
{
    use SoftDeletes;

    protected $table = 'swap_record';

    protected function getDeletedAtColumn()
    {
        return 'delete_time';
    }

    protected function targetImage(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => trim($value) ? FileService::getFileUrl($value) : '',
            set: fn(string $value) => trim($value) ? FileService::setFileUrl($value) : ''
        );
    }

    protected function userImage(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => trim($value) ? FileService::getFileUrl($value) : '',
            set: fn(string $value) => trim($value) ? FileService::setFileUrl($value) : ''
        );
    }

    protected function resultImage(): Attribute
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
