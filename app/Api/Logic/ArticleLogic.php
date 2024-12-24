<?php

namespace App\Api\Logic;

use App\Common\Enum\YesNoEnum;
use App\Common\Logic\BaseLogic;
use App\Common\Model\Article\Article;
use App\Common\Model\Article\ArticleCate;
use App\Common\Model\Article\ArticleCollect;

/**
 * 文章逻辑
 */
class ArticleLogic extends BaseLogic
{
    /**
     * @notes 文章详情
     * @param $articleId
     * @param $userId
     * @return array
     */
    public static function detail($articleId, $userId)
    {
        // 文章详情
        $article = Article::getArticleDetailArr($articleId);
        // 关注状态
        $article['collect'] = ArticleCollect::isCollectArticle($userId, $articleId);

        return $article;
    }

    /**
     * @notes 加入收藏
     * @param $userId
     * @param $articleId
     */
    public static function addCollect($articleId, $userId)
    {
        $where = ['user_id' => $userId, 'article_id' => $articleId];
        $collect = ArticleCollect::where($where)->first();
        if (!$collect) {
            ArticleCollect::create([
                'user_id' => $userId,
                'article_id' => $articleId,
                'status' => YesNoEnum::YES
            ]);
        } else {
            $collect->status = YesNoEnum::YES;
            $collect->save();
        }
    }

    /**
     * @notes 取消收藏
     * @param $articleId
     * @param $userId
     */
    public static function cancelCollect($articleId, $userId)
    {
        ArticleCollect::query()->where([
            'user_id' => $userId,
            'article_id' => $articleId,
            'status' => YesNoEnum::YES
        ])->update(['status' => YesNoEnum::NO]);
    }

    /**
     * @notes 文章分类
     * @return array
     */
    public static function cate()
    {
        return ArticleCate::query()
            ->where('is_show', '=', 1)
            ->orderBy('sort', 'desc')
            ->orderBy('id', 'desc')
            ->get(['id', 'name', 'is_show'])
            ->toArray();
    }

}
