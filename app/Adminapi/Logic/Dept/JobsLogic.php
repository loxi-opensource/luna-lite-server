<?php

namespace App\Adminapi\Logic\Dept;

use App\Common\Enum\YesNoEnum;
use App\Common\Logic\BaseLogic;
use App\Common\Model\Dept\Jobs;

/**
 * 岗位管理逻辑
 */
class JobsLogic extends BaseLogic
{
    /**
     * @notes 新增岗位
     * @param array $params
     */
    public static function add(array $params)
    {
        Jobs::create([
            'name' => $params['name'],
            'code' => $params['code'],
            'sort' => $params['sort'] ?? 0,
            'status' => $params['status'],
            'remark' => $params['remark'] ?? '',
        ]);
    }

    /**
     * @notes 编辑岗位
     * @param array $params
     * @return bool
     */
    public static function edit(array $params): bool
    {
        try {
            Jobs::where('id', $params['id'])->update([
                'name' => $params['name'],
                'code' => $params['code'],
                'sort' => $params['sort'] ?? 0,
                'status' => $params['status'],
                'remark' => $params['remark'] ?? '',
            ]);
            return true;
        } catch (\Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }

    /**
     * @notes 删除岗位
     * @param array $params
     */
    public static function delete(array $params)
    {
        Jobs::destroy($params['id']);
    }

    /**
     * @notes 获取岗位详情
     * @param $params
     * @return array
     */
    public static function detail($params): array
    {
        return Jobs::find($params['id'])->toArray();
    }

    /**
     * @notes 岗位数据
     * @return array
     */
    public static function getAllData()
    {
        return Jobs::where(['status' => YesNoEnum::YES])
            ->orderBy('sort', 'desc')
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();
    }
}
