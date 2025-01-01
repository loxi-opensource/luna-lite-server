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
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * SwapTemplateGroup模型
 * Class SwapTemplateGroup
 * @package App\Common\Model
 */
class SwapTemplateGroup extends BaseModel
{
    use SoftDeletes;

    protected $table = 'swap_template_group';

    protected function getDeletedAtColumn()
    {
        return 'delete_time';
    }

    public function templates()
    {
        return $this->hasMany(SwapTemplate::class, 'group_id', 'id');
    }

}
