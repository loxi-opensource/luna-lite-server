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
