<?php

namespace App\Adminapi\Logic\Setting\Dict;

use App\Common\Logic\BaseLogic;
use App\Common\Model\Dict\DictData;
use App\Common\Model\Dict\DictType;

/**
 * 字典数据逻辑
 */
class DictDataLogic extends BaseLogic
{
    /**
     * @notes 添加或编辑字典数据
     */
    public static function save(array $params)
    {
        $data = [
            'name' => $params['name'],
            'value' => $params['value'],
            'sort' => $params['sort'] ?? 0,
            'status' => $params['status'],
            'remark' => $params['remark'] ?? '',
        ];

        if (!empty($params['id'])) {
            // 编辑现有字典数据
            return DictData::where('id', $params['id'])->update($data);
        } else {
            // 添加新字典数据
            $dictType = DictType::find($params['type_id']);
            if (!$dictType) {
                throw new \Exception('字典类型不存在');
            }
            $data['type_id'] = $params['type_id'];
            $data['type_value'] = $dictType->type; // 使用对象属性
            return DictData::create($data);
        }
    }

    /**
     * @notes 删除字典数据
     */
    public static function delete(array $params)
    {
        return DictData::destroy($params['id']);
    }

    /**
     * @notes 获取字典数据详情
     */
    public static function detail(array $params): array
    {
        $dictData = DictData::find($params['id']);
        if (!$dictData) {
            throw new \Exception('字典数据不存在');
        }
        return $dictData->toArray();
    }

}
