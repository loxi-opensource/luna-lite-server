<?php

namespace App\Adminapi\Controller\Article;

use App\Adminapi\Controller\BaseAdminController;
use App\Adminapi\Lists\Article\ArticleLists;
use App\Adminapi\Logic\Article\ArticleLogic;
use App\Adminapi\Validate\Article\ArticleValidate;

/**
 * 资讯管理控制器
 */
class ArticleController extends BaseAdminController
{

    /**
     * @notes  查看资讯列表
     */
    public function lists()
    {
        return $this->dataLists(new ArticleLists());
    }

    /**
     * @notes  添加资讯
     */
    public function add()
    {
        $params = (new ArticleValidate())->post()->goCheck('add');
        ArticleLogic::add($params);
        return $this->success('添加成功', [], 1, 1);
    }

    /**
     * @notes  编辑资讯
     */
    public function edit()
    {
        $params = (new ArticleValidate())->post()->goCheck('edit');
        $result = ArticleLogic::edit($params);
        if (true === $result) {
            return $this->success('编辑成功', [], 1, 1);
        }
        return $this->fail(ArticleLogic::getError());
    }

    /**
     * @notes  删除资讯
     */
    public function delete()
    {
        $params = (new ArticleValidate())->post()->goCheck('delete');
        ArticleLogic::delete($params);
        return $this->success('删除成功', [], 1, 1);
    }

    /**
     * @notes  资讯详情
     */
    public function detail()
    {
        $params = (new ArticleValidate())->goCheck('detail');
        $result = ArticleLogic::detail($params);
        return $this->data($result);
    }


    /**
     * @notes  更改资讯状态
     */
    public function updateStatus()
    {
        $params = (new ArticleValidate())->post()->goCheck('status');
        $result = ArticleLogic::updateStatus($params);
        if (true === $result) {
            return $this->success('修改成功', [], 1, 1);
        }
        return $this->fail(ArticleLogic::getError());
    }


}
