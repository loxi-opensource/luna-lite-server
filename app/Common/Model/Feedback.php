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
use App\Common\Model\File\File;
use App\Common\Service\FileService;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * 用户反馈模型
 * Class Feedback
 * @package App\Common\Model
 */
class Feedback extends BaseModel
{
    use SoftDeletes;

    protected $table = 'feedback';

    protected $appends = ['type_desc', 'image_list'];

    protected function getDeletedAtColumn()
    {
        return 'delete_time';
    }

    public function getTypeDescAttribute($value)
    {
        $result = [1 => '故障', 2 => '建议', 3 => '投诉'];
        return $result[$this->attributes['type']] ?? '';
    }

    public function getImageListAttribute()
    {
        $fileIds = explode(',', $this->attributes['images']);
        $urls = File::query()->whereIn('id', $fileIds)->pluck('uri');
        $imageList = [];
        foreach ($urls as $url) {
            $imageList[] = FileService::getFileUrl($url);
        }
        return $imageList;
    }

}
