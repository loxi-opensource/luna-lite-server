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
use App\Adminapi\Lists\SwapRecordLists;
use App\Adminapi\Logic\SwapRecordLogic;
use App\Adminapi\Validate\SwapRecordValidate;


/**
 * SwapRecord控制器
 * Class SwapRecordController
 * @package App\Adminapi\Controller
 */
class SwapRecordController extends BaseAdminController
{


    /**
     * @notes 获取列表
     * @author likeadmin
     * @date 2024/12/30 15:36
     */
    public function lists()
    {
        return $this->dataLists(new SwapRecordLists());
    }


    /**
     * @notes 添加
     * @author likeadmin
     * @date 2024/12/30 15:36
     */
    public function add()
    {
        $params = (new SwapRecordValidate())->post()->goCheck('add');
        $result = SwapRecordLogic::add($params);
        if (true === $result) {
            return $this->success('添加成功', [], 1, 1);
        }
        return $this->fail(SwapRecordLogic::getError());
    }


    /**
     * @notes 编辑
     * @author likeadmin
     * @date 2024/12/30 15:36
     */
    public function edit()
    {
        $params = (new SwapRecordValidate())->post()->goCheck('edit');
        $result = SwapRecordLogic::edit($params);
        if (true === $result) {
            return $this->success('编辑成功', [], 1, 1);
        }
        return $this->fail(SwapRecordLogic::getError());
    }


    /**
     * @notes 删除
     * @author likeadmin
     * @date 2024/12/30 15:36
     */
    public function delete()
    {
        $params = (new SwapRecordValidate())->post()->goCheck('delete');
        SwapRecordLogic::delete($params);
        return $this->success('删除成功', [], 1, 1);
    }


    /**
     * @notes 获取详情
     * @author likeadmin
     * @date 2024/12/30 15:36
     */
    public function detail()
    {
        $params = (new SwapRecordValidate())->goCheck('detail');
        $result = SwapRecordLogic::detail($params);
        return $this->data($result);
    }


}
