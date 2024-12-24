<?php

namespace App\Adminapi\Logic\Article;

use App\Common\Enum\YesNoEnum;
use App\Common\Logic\BaseLogic;
use App\Common\Model\Article\ArticleCate;

/**
 * 资讯分类管理逻辑
 */
class ArticleCateLogic extends BaseLogic
{
    /**
     * @notes 添加资讯分类
     */
    public static function add(array $params)
    {
        ArticleCate::create([
            'name' => $params['name'],
            'is_show' => $params['is_show'],
            'sort' => $params['sort'] ?? 0,
        ]);
    }

    /**
     * @notes 编辑资讯分类
     */
    public static function edit(array $params): bool
    {
        try {
            ArticleCate::where('id', $params['id'])->update([
                'name' => $params['name'],
                'is_show' => $params['is_show'],
                'sort' => $params['sort'] ?? 0,
            ]);
            return true;
        } catch (\Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }

    /**
     * @notes 删除资讯分类
     */
    public static function delete(array $params)
    {
        ArticleCate::destroy($params['id']);
    }

    /**
     * @notes 查看资讯分类详情
     */
    public static function detail($params): array
    {
        return ArticleCate::findOrFail($params['id'])->toArray();
    }

    /**
     * @notes 更改资讯分类状态
     */
    public static function updateStatus(array $params): bool
    {
        ArticleCate::where('id', $params['id'])->update([
            'is_show' => $params['is_show'],
        ]);
        return true;
    }

    /**
     * @notes 获取文章分类数据
     */
    public static function getAllData()
    {
        return ArticleCate::where('is_show', YesNoEnum::YES)
            ->orderBy('sort', 'desc')
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();
    }
}
