<?php

namespace App\Api\Controller;

use App\Api\Lists\Article\ArticleCollectLists;
use App\Api\Lists\Article\ArticleLists;
use App\Api\Logic\ArticleLogic;

/**
 * 文章管理
 */
class ArticleController extends BaseApiController
{

    public array $notNeedLogin = ['lists', 'cate', 'detail'];

    /**
     * @notes 文章列表
     */
    public function lists()
    {
        return $this->dataLists(new ArticleLists());
    }

    /**
     * @notes 文章分类列表
     */
    public function cate()
    {
        return $this->data(ArticleLogic::cate());
    }

    /**
     * @notes 收藏列表
     */
    public function collect()
    {
        return $this->dataLists(new ArticleCollectLists());
    }

    /**
     * @notes 文章详情
     */
    public function detail()
    {
        $id = $this->request->get('id');
        $result = ArticleLogic::detail($id, $this->getUserId());
        return $this->data($result);
    }

    /**
     * @notes 加入收藏
     */
    public function addCollect()
    {
        $articleId = $this->request->post('id');
        ArticleLogic::addCollect($articleId, $this->getUserId());
        return $this->success('操作成功');
    }

    /**
     * @notes 取消收藏
     */
    public function cancelCollect()
    {
        $articleId = $this->request->post('id');
        ArticleLogic::cancelCollect($articleId, $this->getUserId());
        return $this->success('操作成功');
    }


}
