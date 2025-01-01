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

namespace App\Adminapi\Logic;


use App\Common\Model\SwapTemplate;
use App\Common\Logic\BaseLogic;
use Illuminate\Support\Facades\DB;


/**
 * SwapTemplate逻辑
 * Class SwapTemplateLogic
 * @package App\Adminapi\Logic
 */
class SwapTemplateLogic extends BaseLogic
{


    /**
     * @notes 添加
     * @param array $params
     * @return bool
     * @author likeadmin
     * @date 2024/12/30 14:54
     */
    public static function add(array $params): bool
    {
        DB::beginTransaction();
        try {
            SwapTemplate::create([
                'name' => $params['name'],
                'status' => $params['status'],
                'group_id' => $params['group_id'],
                'target_image' => $params['target_image'],
                'sort' => $params['sort'],
            ]);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            self::setError($e->getMessage());
            return false;
        }
    }


    /**
     * @notes 编辑
     * @param array $params
     * @return bool
     * @author likeadmin
     * @date 2024/12/30 14:54
     */
    public static function edit(array $params): bool
    {
        DB::beginTransaction();
        try {
            $model = SwapTemplate::findOrFail($params['id']);
            $model->fill([
                'name' => $params['name'],
                'status' => $params['status'],
                'group_id' => $params['group_id'],
                'target_image' => $params['target_image'],
                'sort' => $params['sort'],
            ]);
            $model->save();
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            self::setError($e->getMessage());
            return false;
        }
    }


    /**
     * @notes 删除
     * @param array $params
     * @return bool
     * @author likeadmin
     * @date 2024/12/30 14:54
     */
    public static function delete(array $params): bool
    {
        return SwapTemplate::destroy($params['id']);
    }


    /**
     * @notes 获取详情
     * @param $params
     * @return array
     * @author likeadmin
     * @date 2024/12/30 14:54
     */
    public static function detail($params): array
    {
        return SwapTemplate::findOrFail($params['id'])->toArray();
    }
}
