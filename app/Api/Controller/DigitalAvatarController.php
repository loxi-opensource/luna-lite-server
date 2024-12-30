<?php

namespace App\Api\Controller;

use App\Common\Model\DigitalAvatar;
use http\Env\Request;

class DigitalAvatarController extends BaseApiController
{
    public function list()
    {
        $list = DigitalAvatar::query()
            ->where('user_id', $this->getUserId())
            ->orderByDesc('id')
            ->limit(9)
            ->get();
        return $this->success('Success', compact('list'));
    }

    public function remove(Request $request)
    {
        DigitalAvatar::query()
            ->where('user_id', $this->getUserId())
            ->where('id', $request->post('id'))
            ->delete();
        return $this->success('Success');
    }

}
