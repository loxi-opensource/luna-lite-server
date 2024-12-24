<?php

namespace App\Adminapi\Logic\Setting\Dict;

use App\Common\Enum\YesNoEnum;
use App\Common\Logic\BaseLogic;
use App\Common\Model\Dict\DictData;
use App\Common\Model\Dict\DictType;

/**
 * 字典类型逻辑
 */
class DictTypeLogic extends BaseLogic
{
    /**
     * @notes 添加字典类型
     */
    public static function add(array $params)
    {
        return DictType::create([
            'name' => $params['name'],
            'type' => $params['type'],
            'status' => $params['status'],
            'remark' => $params['remark'] ?? '',
        ]);
    }

    /**
     * @notes 编辑字典类型
     */
    public static function edit(array $params)
    {
        $dictType = DictType::query()->find($params['id']);
        if (!$dictType) {
            throw new \Exception('字典类型不存在');
        }

        $dictType->update([
            'name' => $params['name'],
            'type' => $params['type'],
            'status' => $params['status'],
            'remark' => $params['remark'] ?? '',
        ]);

        DictData::where('type_id', $params['id'])
            ->update(['type_value' => $params['type']]);
    }

    /**
     * @notes 删除字典类型
     */
    public static function delete(array $params)
    {
        DictType::destroy($params['id']);
    }

    /**
     * @notes 获取字典详情
     */
    public static function detail(array $params): array
    {
        $dictType = DictType::find($params['id']);
        if (!$dictType) {
            throw new \Exception('字典类型不存在');
        }
        return $dictType->toArray();
    }

    /**
     * @notes 获取所有有效字典类型
     */
    public static function getAllData()
    {
        return DictType::where('status', YesNoEnum::YES)
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();
    }
}
