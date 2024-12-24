<?php

namespace App\Adminapi\Controller\Finance;

use App\Adminapi\Controller\BaseAdminController;
use App\Adminapi\Lists\Finance\AccountLogLists;
use App\Common\Enum\User\AccountLogEnum;

/***
 * 账户流水控制器
 */
class AccountLogController extends BaseAdminController
{
    /**
     * @notes 账户流水明细
     */
    public function lists()
    {
        return $this->dataLists(new AccountLogLists());
    }


    /**
     * @notes 用户余额变动类型
     */
    public function getUmChangeType()
    {
        return $this->data(AccountLogEnum::getUserMoneyChangeTypeDesc());
    }

}
