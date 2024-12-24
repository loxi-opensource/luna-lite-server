<?php

namespace App\Adminapi\Controller\Finance;

use App\Adminapi\Controller\BaseAdminController;
use App\Adminapi\Lists\Finance\RefundRecordLists;
use App\Adminapi\Logic\Finance\RefundLogic;

/**
 * 退款控制器
 */
class RefundController extends BaseAdminController
{
    /**
     * @notes 退还统计
     */
    public function stat()
    {
        $result = RefundLogic::stat();
        return $this->success('', $result);
    }

    /**
     * @notes 退款记录
     */
    public function record()
    {
        return $this->dataLists(new RefundRecordLists());
    }

    /**
     * @notes 退款日志
     */
    public function log()
    {
        $recordId = $this->request->get('record_id', 0);
        $result = RefundLogic::refundLog($recordId);
        return $this->success('', $result);
    }

}
