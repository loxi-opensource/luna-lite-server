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

namespace App\Adminapi\Lists;


use App\Adminapi\Lists\BaseAdminDataLists;
use App\Common\Model\SwapRecord;
use App\Common\Lists\ListsSearchInterface;


/**
 * SwapRecord列表
 * Class SwapRecordLists
 * @package App\Adminapi\Lists
 */
class SwapRecordLists extends BaseAdminDataLists implements ListsSearchInterface
{


    /**
     * @notes 设置搜索条件
     * @return \string[][]
     * @author likeadmin
     * @date 2024/12/30 15:36
     */
    public function setSearch(): array
    {
        return [
            '=' => ['user_id'],
        ];
    }


    /**
     * @notes 获取列表
     * @return array
     * @author likeadmin
     * @date 2024/12/30 15:36
     */
    public function lists(): array
    {
        return SwapRecord::applySearchWhere($this->searchWhere)
            ->select(['id', 'user_id', 'target_image', 'user_image', 'result_image', 'create_time'])
            ->limit($this->limitLength)
            ->offset($this->limitOffset)
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();
    }


    /**
     * @notes 获取数量
     * @return int
     * @author likeadmin
     * @date 2024/12/30 15:36
     */
    public function count(): int
    {
        return SwapRecord::applySearchWhere($this->searchWhere)->count();
    }

}