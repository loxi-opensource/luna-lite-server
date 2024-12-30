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
use App\Common\Model\SwapTemplate;
use App\Common\Lists\ListsSearchInterface;


/**
 * SwapTemplate列表
 * Class SwapTemplateLists
 * @package App\Adminapi\Lists
 */
class SwapTemplateLists extends BaseAdminDataLists implements ListsSearchInterface
{


    /**
     * @notes 设置搜索条件
     * @return \string[][]
     * @author likeadmin
     * @date 2024/12/30 14:54
     */
    public function setSearch(): array
    {
        return [
            '=' => ['status', 'group_id'],
            '%like%' => ['name'],
        ];
    }


    /**
     * @notes 获取列表
     * @return array
     * @author likeadmin
     * @date 2024/12/30 14:54
     */
    public function lists(): array
    {
        return SwapTemplate::applySearchWhere($this->searchWhere)
            ->select(['id', 'name', 'status', 'group_id', 'target_image'])
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
     * @date 2024/12/30 14:54
     */
    public function count(): int
    {
        return SwapTemplate::applySearchWhere($this->searchWhere)->count();
    }

}
