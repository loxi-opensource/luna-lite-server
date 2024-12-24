<?php

namespace App\Adminapi\Logic\Article;

use App\Common\Logic\BaseLogic;
use App\Common\Model\Article\Article;
use App\Common\Service\FileService;

/**
 * 资讯管理逻辑
 * Class ArticleLogic
 * @package App\Adminapi\Logic\Article
 */
class ArticleLogic extends BaseLogic
{
    /**
     * @notes 添加资讯
     * @param array $params
     */
    public static function add(array $params)
    {
        Article::create([
            'title' => $params['title'],
            'desc' => $params['desc'] ?? '',
            'author' => $params['author'] ?? '',
            'sort' => $params['sort'] ?? 0,
            'abstract' => $params['abstract'],
            'click_virtual' => $params['click_virtual'] ?? 0,
            'image' => $params['image'] ? FileService::setFileUrl($params['image']) : '',
            'cid' => $params['cid'],
            'is_show' => $params['is_show'],
            'content' => $params['content'] ?? '',
        ]);
    }

    /**
     * @notes 编辑资讯
     * @param array $params
     * @return bool
     */
    public static function edit(array $params): bool
    {
        try {
            Article::where('id', $params['id'])->update([
                'title' => $params['title'],
                'desc' => $params['desc'] ?? '',
                'author' => $params['author'] ?? '',
                'sort' => $params['sort'] ?? 0,
                'abstract' => $params['abstract'],
                'click_virtual' => $params['click_virtual'] ?? 0,
                'image' => $params['image'] ? FileService::setFileUrl($params['image']) : '',
                'cid' => $params['cid'],
                'is_show' => $params['is_show'],
                'content' => $params['content'] ?? '',
            ]);
            return true;
        } catch (\Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }

    /**
     * @notes 删除资讯
     * @param array $params
     */
    public static function delete(array $params)
    {
        Article::destroy($params['id']);
    }

    /**
     * @notes 查看资讯详情
     * @param $params
     * @return array
     */
    public static function detail($params): array
    {
        return Article::findOrFail($params['id'])->toArray();
    }

    /**
     * @notes 更改资讯状态
     * @param array $params
     * @return bool
     */
    public static function updateStatus(array $params): bool
    {
        Article::where('id', $params['id'])->update([
            'is_show' => $params['is_show']
        ]);
        return true;
    }
}
