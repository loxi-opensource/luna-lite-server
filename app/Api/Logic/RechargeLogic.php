<?php

namespace App\Api\Logic;

use App\Common\Enum\PayEnum;
use App\Common\Enum\YesNoEnum;
use App\Common\Logic\BaseLogic;
use App\Common\Model\Recharge\RechargeOrder;
use App\Common\Model\RechargePackage;
use App\Common\Model\User\User;
use App\Common\Service\ConfigService;
use Throwable;

/**
 * 充值逻辑层
 */
class RechargeLogic extends BaseLogic
{

    /**
     * @notes 充值
     */
    public static function recharge(array $params): array|false
    {
        try {
            $rechargePackage = RechargePackage::query()->findOrFail($params['package_id']);

            $data = [
                'sn' => generate_sn(RechargeOrder::class, 'sn'),
                'order_terminal' => $params['terminal'],
                'user_id' => $params['user_id'],
                'pay_status' => PayEnum::UNPAID,
                'order_amount' => $rechargePackage->sell_price,
                'recharge_package_snapshot' => $rechargePackage,
            ];
            $order = new RechargeOrder();
            $order->fill($data)->save();

            return [
                'order_id' => (int)$order->id,
                'from' => 'recharge'
            ];
        } catch (Throwable $e) {
            self::setError($e->getMessage());
            return false;
        }
    }


    /**
     * @notes 充值配置
     */
    public static function config($userId): array
    {
        $userMoney = User::where('id', $userId)->value('user_money');
        $minAmount = ConfigService::get('recharge', 'min_amount', 0);
        $status = ConfigService::get('recharge', 'status', 0);

        return [
            'status' => $status,
            'min_amount' => $minAmount,
            'user_money' => $userMoney,
        ];
    }

    public static function getAllPackages()
    {
        return RechargePackage::where(['status' => YesNoEnum::YES])
            ->orderBy('sort', 'desc')
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();
    }
}

