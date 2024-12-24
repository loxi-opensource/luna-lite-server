<?php


namespace App\Common\Model\Article;

use App\Common\Model\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 资讯分类管理模型
 */
class ArticleCate extends BaseModel
{
    protected $table = 'article_cate';

    use SoftDeletes;

    protected $appends = [
        'is_show_desc',
        'article_count'
    ];

    protected function getDeletedAtColumn()
    {
        return 'delete_time';
    }

    /**
     * @notes 关联文章
     */
    public function article()
    {
        return $this->hasMany(Article::class, 'cid', 'id');
    }


    /**
     * @notes 状态描述
     * @param $value
     * @param $data
     * @return string
     */
    public function getIsShowDescAttribute($value)
    {
        if (!isset($this->attributes['is_show'])) {
            return '';
        }
        return $this->attributes['is_show'] ? '启用' : '停用';
    }

    /**
     * @notes 文章数量
     * @param $value
     * @param $data
     * @return int
     */
    public function getArticleCountAttribute($value)
    {
        return Article::where(['cid' => $this->attributes['id']])->count('id');
    }

}
