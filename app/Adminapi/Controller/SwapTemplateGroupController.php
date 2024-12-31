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


namespace App\Adminapi\Controller;


use App\Adminapi\Controller\BaseAdminController;
use App\Adminapi\Lists\SwapTemplateGroupLists;
use App\Adminapi\Logic\Article\ArticleCateLogic;
use App\Adminapi\Logic\SwapTemplateGroupLogic;
use App\Adminapi\Validate\SwapTemplateGroupValidate;


/**
 * SwapTemplateGroup控制器
 * Class SwapTemplateGroupController
 * @package App\Adminapi\Controller
 */
class SwapTemplateGroupController extends BaseAdminController
{


    /**
     * @notes 获取列表
     * @author likeadmin
     * @date 2024/12/30 14:44
     */
    public function lists()
    {
        return $this->dataLists(new SwapTemplateGroupLists());
    }


    /**
     * @notes 添加
     * @author likeadmin
     * @date 2024/12/30 14:44
     */
    public function add()
    {
        $params = (new SwapTemplateGroupValidate())->post()->goCheck('add');
        $result = SwapTemplateGroupLogic::add($params);
        if (true === $result) {
            return $this->success('添加成功', [], 1, 1);
        }
        return $this->fail(SwapTemplateGroupLogic::getError());
    }


    /**
     * @notes 编辑
     * @author likeadmin
     * @date 2024/12/30 14:44
     */
    public function edit()
    {
        $params = (new SwapTemplateGroupValidate())->post()->goCheck('edit');
        $result = SwapTemplateGroupLogic::edit($params);
        if (true === $result) {
            return $this->success('编辑成功', [], 1, 1);
        }
        return $this->fail(SwapTemplateGroupLogic::getError());
    }


    /**
     * @notes 删除
     * @author likeadmin
     * @date 2024/12/30 14:44
     */
    public function delete()
    {
        $params = (new SwapTemplateGroupValidate())->post()->goCheck('delete');
        SwapTemplateGroupLogic::delete($params);
        return $this->success('删除成功', [], 1, 1);
    }


    /**
     * @notes 获取详情
     * @author likeadmin
     * @date 2024/12/30 14:44
     */
    public function detail()
    {
        $params = (new SwapTemplateGroupValidate())->goCheck('detail');
        $result = SwapTemplateGroupLogic::detail($params);
        return $this->data($result);
    }

    /**
     * @notes 获取全部分组
     */
    public function all()
    {
        $result = SwapTemplateGroupLogic::getAllData();
        return $this->data($result);
    }

}
