<?php

namespace App\Api\Logic;

use App\Common\Logic\BaseLogic;
use App\Common\Model\Feedback;

class FeedbackLogic extends BaseLogic
{
    public function add($params)
    {
        return Feedback::create([
            'user_id' => $params['user_id'],
            'type' => $params['type'],
            'content' => $params['content'],
            'mobile' => $params['mobile'],
            'images' => implode(',', $params['images'] ?? []),
        ]);
    }
}
