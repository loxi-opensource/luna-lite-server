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
use App\Adminapi\Lists\FeedbackLists;
use App\Adminapi\Logic\FeedbackLogic;
use App\Adminapi\Validate\FeedbackValidate;


/**
 * 用户反馈控制器
 * Class FeedbackController
 * @package App\Adminapi\Controller
 */
class FeedbackController extends BaseAdminController
{


    /**
     * @notes 获取用户反馈列表
     * @author likeadmin
     * @date 2024/12/31 15:27
     */
    public function lists()
    {
        return $this->dataLists(new FeedbackLists());
    }


    /**
     * @notes 添加用户反馈
     * @author likeadmin
     * @date 2024/12/31 15:27
     */
    public function add()
    {
        $params = (new FeedbackValidate())->post()->goCheck('add');
        $result = FeedbackLogic::add($params);
        if (true === $result) {
            return $this->success('添加成功', [], 1, 1);
        }
        return $this->fail(FeedbackLogic::getError());
    }


    /**
     * @notes 编辑用户反馈
     * @author likeadmin
     * @date 2024/12/31 15:27
     */
    public function edit()
    {
        $params = (new FeedbackValidate())->post()->goCheck('edit');
        $result = FeedbackLogic::edit($params);
        if (true === $result) {
            return $this->success('编辑成功', [], 1, 1);
        }
        return $this->fail(FeedbackLogic::getError());
    }


    /**
     * @notes 删除用户反馈
     * @author likeadmin
     * @date 2024/12/31 15:27
     */
    public function delete()
    {
        $params = (new FeedbackValidate())->post()->goCheck('delete');
        FeedbackLogic::delete($params);
        return $this->success('删除成功', [], 1, 1);
    }


    /**
     * @notes 获取用户反馈详情
     * @author likeadmin
     * @date 2024/12/31 15:27
     */
    public function detail()
    {
        $params = (new FeedbackValidate())->goCheck('detail');
        $result = FeedbackLogic::detail($params);
        return $this->data($result);
    }


}
