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

    
}
