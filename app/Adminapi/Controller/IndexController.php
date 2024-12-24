<?php

namespace App\Adminapi\Controller;

use Illuminate\Http\JsonResponse;

class IndexController extends BaseAdminController
{
//    public array $notNeedLogin = ['index'];

    public function index(): JsonResponse
    {
        $domain = request()->schemeAndHttpHost();

        return $this->data([
            "message" => "Hello Adminapi",
            'a' => $this->request->attributes->get('adminInfo'),
            'b' => $domain,
            'c' => $this->getAdminId()
        ]);
    }
}
