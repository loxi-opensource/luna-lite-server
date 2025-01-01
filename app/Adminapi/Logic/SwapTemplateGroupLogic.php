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


use App\Common\Enum\YesNoEnum;
use App\Common\Model\Article\ArticleCate;
use App\Common\Model\SwapTemplateGroup;
use App\Common\Logic\BaseLogic;
use Illuminate\Support\Facades\DB;


/**
 * SwapTemplateGroup逻辑
 * Class SwapTemplateGroupLogic
 * @package App\Adminapi\Logic
 */
class SwapTemplateGroupLogic extends BaseLogic
{


    /**
     * @notes 添加
     * @param array $params
     * @return bool
     * @author likeadmin
     * @date 2024/12/30 14:44
     */
    public static function add(array $params): bool
    {
        DB::beginTransaction();
        try {
            SwapTemplateGroup::create([
                'name' => $params['name'],
                'status' => $params['status'],
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
     * @date 2024/12/30 14:44
     */
    public static function edit(array $params): bool
    {
        DB::beginTransaction();
        try {
            SwapTemplateGroup::where('id', $params['id'])->update([
                'name' => $params['name'],
                'status' => $params['status'],
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
     * @notes 删除
     * @param array $params
     * @return bool
     * @author likeadmin
     * @date 2024/12/30 14:44
     */
    public static function delete(array $params): bool
    {
        return SwapTemplateGroup::destroy($params['id']);
    }


    /**
     * @notes 获取详情
     * @param $params
     * @return array
     * @author likeadmin
     * @date 2024/12/30 14:44
     */
    public static function detail($params): array
    {
        return SwapTemplateGroup::findOrFail($params['id'])->toArray();
    }

    public static function getAllData()
    {
        return SwapTemplateGroup::where('status', YesNoEnum::YES)
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();
    }

}
