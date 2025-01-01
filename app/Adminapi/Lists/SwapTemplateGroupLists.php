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

namespace App\Adminapi\Lists;


use App\Adminapi\Lists\BaseAdminDataLists;
use App\Common\Model\SwapTemplateGroup;
use App\Common\Lists\ListsSearchInterface;


/**
 * SwapTemplateGroup列表
 * Class SwapTemplateGroupLists
 * @package App\Adminapi\Lists
 */
class SwapTemplateGroupLists extends BaseAdminDataLists implements ListsSearchInterface
{


    /**
     * @notes 设置搜索条件
     * @return \string[][]
     * @author likeadmin
     * @date 2024/12/30 14:44
     */
    public function setSearch(): array
    {
        return [

        ];
    }


    /**
     * @notes 获取列表
     * @return array
     * @author likeadmin
     * @date 2024/12/30 14:44
     */
    public function lists(): array
    {
        return SwapTemplateGroup::applySearchWhere($this->searchWhere)
            ->select(['id', 'name', 'status'])
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
     * @date 2024/12/30 14:44
     */
    public function count(): int
    {
        return SwapTemplateGroup::applySearchWhere($this->searchWhere)->count();
    }

}
