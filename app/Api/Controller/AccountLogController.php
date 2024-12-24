<?php

namespace App\Api\Controller;

use App\Api\Lists\AccountLogLists;

/**
 * 账户流水
 */
class AccountLogController extends BaseApiController
{
    /**
     * @notes 账户流水
     */
    public function lists()
    {
        return $this->dataLists(new AccountLogLists());
    }

}
