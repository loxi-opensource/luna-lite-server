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
use App\Common\Model\SwapPageConfig;
use App\Common\Lists\ListsSearchInterface;


/**
 * 换脸页面配置列表
 * Class SwapPageConfigLists
 * @package App\Adminapi\Lists
 */
class SwapPageConfigLists extends BaseAdminDataLists implements ListsSearchInterface
{


    /**
     * @notes 设置搜索条件
     * @return \string[][]
     * @author likeadmin
     * @date 2024/12/31 16:16
     */
    public function setSearch(): array
    {
        return [
            
        ];
    }


    /**
     * @notes 获取换脸页面配置列表
     * @return array
     * @author likeadmin
     * @date 2024/12/31 16:16
     */
    public function lists(): array
    {
        return SwapPageConfig::applySearchWhere($this->searchWhere)
            ->select(['id', 'name', 'page_data'])
            ->limit($this->limitLength)
            ->offset($this->limitOffset)
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();
    }


    /**
     * @notes 获取换脸页面配置数量
     * @return int
     * @author likeadmin
     * @date 2024/12/31 16:16
     */
    public function count(): int
    {
        return SwapPageConfig::applySearchWhere($this->searchWhere)->count();
    }

}
