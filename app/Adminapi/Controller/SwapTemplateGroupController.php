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
