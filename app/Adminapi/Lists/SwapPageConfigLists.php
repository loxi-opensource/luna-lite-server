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
use app\common\model\OldGoodsPoolCategory;
use App\Common\Model\SwapPageConfig;
use App\Common\Lists\ListsSearchInterface;
use App\Common\Model\SwapTemplateGroup;
use Illuminate\Support\Facades\Log;


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
        $rows = SwapPageConfig::applySearchWhere($this->searchWhere)
            ->select(['id', 'name', 'page_data'])
            ->limit($this->limitLength)
            ->offset($this->limitOffset)
            ->orderBy('id', 'desc')
            ->get();

        // 获取所有模板组
        $allTemplateGroups = SwapTemplateGroup::query()
            ->where('status', 1)
            ->select(['id', 'name'])
            ->get();

        // 处理每个配置
        $rows = $rows->map(function ($row) use ($allTemplateGroups) {
            $pageData = $row->page_data;

            // 处理 show_list
            $pageData['show_list'] = collect($pageData['show_list'] ?? [])
                ->map(function ($item) use ($allTemplateGroups) {
                    $templateInfo = $allTemplateGroups->firstWhere('id', $item['id']);
                    return $templateInfo ? array_merge($item, $templateInfo->toArray()) : $item;
                })
                ->values()
                ->toArray();

            // 处理 pending_list
            $usedIds = collect($pageData['show_list'])->pluck('id')->toArray();
            $pageData['pending_list'] = $allTemplateGroups
                ->reject(function ($item) use ($usedIds) {
                    return in_array($item->id, $usedIds);
                })
                ->values()
                ->toArray();

            $row->page_data = $pageData;
            return $row;
        });

        return $rows->toArray();

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
