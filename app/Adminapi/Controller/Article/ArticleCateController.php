<?php

namespace App\Adminapi\Controller\Article;

use App\Adminapi\Controller\BaseAdminController;
use App\Adminapi\Lists\Article\ArticleCateLists;
use App\Adminapi\Logic\Article\ArticleCateLogic;
use App\Adminapi\Validate\Article\ArticleCateValidate;

/**
 * 资讯分类管理控制器
 */
class ArticleCateController extends BaseAdminController
{

    /**
     * @notes  查看资讯分类列表
     */
    public function lists()
    {
        return $this->dataLists(new ArticleCateLists());
    }


    /**
     * @notes  添加资讯分类
     */
    public function add()
    {
        $params = (new ArticleCateValidate())->post()->goCheck('add');
        ArticleCateLogic::add($params);
        return $this->success('添加成功', [], 1, 1);
    }


    /**
     * @notes  编辑资讯分类
     */
    public function edit()
    {
        $params = (new ArticleCateValidate())->post()->goCheck('edit');
        $result = ArticleCateLogic::edit($params);
        if (true === $result) {
            return $this->success('编辑成功', [], 1, 1);
        }
        return $this->fail(ArticleCateLogic::getError());
    }


    /**
     * @notes  删除资讯分类
     */
    public function delete()
    {
        $params = (new ArticleCateValidate())->post()->goCheck('delete');
        ArticleCateLogic::delete($params);
        return $this->success('删除成功', [], 1, 1);
    }


    /**
     * @notes  资讯分类详情
     */
    public function detail()
    {
        $params = (new ArticleCateValidate())->goCheck('detail');
        $result = ArticleCateLogic::detail($params);
        return $this->data($result);
    }


    /**
     * @notes  更改资讯分类状态
     */
    public function updateStatus()
    {
        $params = (new ArticleCateValidate())->post()->goCheck('status');
        $result = ArticleCateLogic::updateStatus($params);
        if (true === $result) {
            return $this->success('修改成功', [], 1, 1);
        }
        return $this->fail(ArticleCateLogic::getError());
    }


    /**
     * @notes 获取文章分类
     */
    public function all()
    {
        $result = ArticleCateLogic::getAllData();
        return $this->data($result);
    }


}
