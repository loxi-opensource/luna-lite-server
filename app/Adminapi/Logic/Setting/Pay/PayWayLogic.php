<?php

namespace App\Adminapi\Logic\Setting\Pay;

use App\Common\Enum\PayEnum;
use App\Common\Enum\YesNoEnum;
use App\Common\Logic\BaseLogic;
use App\Common\Model\Pay\PayConfig;
use App\Common\Model\Pay\PayWay;
use App\Common\Service\FileService;

/**
 * 支付方式
 */
class PayWayLogic extends BaseLogic
{
    /**
     * @notes 获取支付方式
     */
    public static function getPayWay()
    {
        $payWay = PayWay::get()->toArray();

        if (empty($payWay)) {
            return [];
        }

        $lists = [];
        for ($i = 1; $i <= max(array_column($payWay, 'scene')); $i++) {
            foreach ($payWay as $val) {
                if ($val['scene'] == $i) {
                    $val['icon'] = FileService::getFileUrl(PayConfig::where('id', $val['pay_config_id'])->value('icon'));
                    $lists[$i][] = $val;
                }
            }
        }

        return $lists;
    }


    /**
     * @notes 设置支付方式
     */
    public static function setPayWay($params)
    {
        $payWay = new PayWay;
        $data = [];
        foreach ($params as $key => $value) {
            $isDefault = array_column($value, 'is_default');
            $isDefaultNum = array_count_values($isDefault);
            $status = array_column($value, 'status');
            $sceneName = PayEnum::getPaySceneDesc($key);
            if (!in_array(YesNoEnum::YES, $isDefault)) {
                return $sceneName . '支付场景缺少默认支付';
            }
            if ($isDefaultNum[YesNoEnum::YES] > 1) {
                return $sceneName . '支付场景的默认值只能存在一个';
            }
            if (!in_array(YesNoEnum::YES, $status)) {
                return $sceneName . '支付场景至少开启一个支付状态';
            }

            foreach ($value as $val) {
                $result = PayWay::find($val['id']);
                if (!$result) {
                    continue;
                }
                if ($val['is_default'] == YesNoEnum::YES && $val['status'] == YesNoEnum::NO) {
                    return $sceneName . '支付场景的默认支付未开启支付状态';
                }

                $result->is_default = $val['is_default'];
                $result->status = $val['status'];
                $result->save();
            }
        }

        return true;
    }
}

