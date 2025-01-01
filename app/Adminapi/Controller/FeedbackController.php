<?php
// +----------------------------------------------------------------------
// | Luna-Swapping开源AI换脸项目Lite版本（基于likeadmin_laravel框架）
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | Github开源仓库：https://github.com/loxi-opensource/luna-lite-server
// | 全栈开发框架：https://github.com/1nFrastr/likeadmin_laravel
// | 独立开发者博客：https://www.sodair.top
// +----------------------------------------------------------------------
// | author: 1nFrastr x likeadminTeam
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
