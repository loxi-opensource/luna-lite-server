<?php
// +----------------------------------------------------------------------
// | likeadmin快速开发前后端分离管理后台（PHP版）
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | gitee下载：https://gitee.com/likeshop_gitee/likeadmin
// | github下载：https://github.com/likeshop-github/likeadmin
// | 访问官网：https://www.likeadmin.cn
// | likeadmin团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeadminTeam
// +----------------------------------------------------------------------

namespace App\Common\Model;


use App\Common\Model\BaseModel;
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

    protected $appends = ['type_desc'];

    protected function getDeletedAtColumn()
    {
        return 'delete_time';
    }

    public function getTypeDescAttribute($value)
    {
        $result = [1 => '故障', 2 => '建议', 3 => '投诉'];
        return $result[$this->attributes['type']] ?? '';
    }

}
