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
use App\Common\Model\RechargePackage;
use App\Common\Lists\ListsSearchInterface;


/**
 * RechargePackage列表
 * Class RechargePackageLists
 * @package App\Adminapi\Lists
 */
class RechargePackageLists extends BaseAdminDataLists implements ListsSearchInterface
{


    /**
     * @notes 设置搜索条件
     * @return \string[][]
     * @author likeadmin
     * @date 2025/01/30 16:55
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
     * @date 2025/01/30 16:55
     */
    public function lists(): array
    {
        return RechargePackage::applySearchWhere($this->searchWhere)
            ->select(['id', 'name', 'describe', 'sell_price', 'draw_number', 'sort', 'status'])
            ->limit($this->limitLength)
            ->offset($this->limitOffset)
            ->orderBy('sort', 'desc')
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();
    }


    /**
     * @notes 获取数量
     * @return int
     * @author likeadmin
     * @date 2025/01/30 16:55
     */
    public function count(): int
    {
        return RechargePackage::applySearchWhere($this->searchWhere)->count();
    }

}
