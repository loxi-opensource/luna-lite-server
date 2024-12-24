<?php

namespace App\Common\Model\Article;

use App\Common\Enum\YesNoEnum;
use App\Common\Model\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 资讯收藏
 */
class ArticleCollect extends BaseModel
{
    use SoftDeletes;

    protected $table = 'article_collect';

    protected function getDeletedAtColumn()
    {
        return 'delete_time';
    }

    /**
     * 是否已收藏文章
     * @param int $userId
     * @param int $articleId
     * @return bool (true=已收藏, false=未收藏)
     */
    public static function isCollectArticle(int $userId, int $articleId): bool
    {
        $collect = self::where([
            'user_id' => $userId,
            'article_id' => $articleId,
            'status' => YesNoEnum::YES
        ])->first();

        return !is_null($collect); // 如果找到了收藏记录则返回 true
    }
}
