<?php

namespace App\Adminapi\Logic\Decorate;

use App\Common\Logic\BaseLogic;
use App\Common\Model\Decorate\DecoratePage;
use Throwable;

/**
 * 装修页面
 */
class DecoratePageLogic extends BaseLogic
{
    /**
     * @notes 获取详情
     */
    public static function getDetail($id): array
    {
        return DecoratePage::findOrFail($id)->toArray();
    }

    /**
     * @notes 保存装修配置
     */
    public static function save(array $params): bool
    {
        try {
            $pageData = DecoratePage::find($params['id']);
            if (!$pageData) {
                self::$error = '信息不存在';
                return false;
            }
            $pageData->update([
                'type' => $params['type'],
                'data' => $params['data'],
                'meta' => $params['meta'] ?? '',
            ]);
            return true;
        } catch (Throwable $e) {
            self::$error = $e->getMessage();
            return false;
        }
    }
}
