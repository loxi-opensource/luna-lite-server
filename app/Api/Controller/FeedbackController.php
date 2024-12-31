<?php

namespace App\Api\Controller;

use App\Api\Logic\FeedbackLogic;
use App\Api\Validate\FeedbackValidate;

class FeedbackController extends BaseApiController
{
    public function add()
    {
        $params = (new FeedbackValidate())->post()->goCheck('add', ['user_id' => $this->getUserId()]);
        (new FeedbackLogic())->add($params);
        return $this->success('提交成功', [], 1, 1);
    }
}
