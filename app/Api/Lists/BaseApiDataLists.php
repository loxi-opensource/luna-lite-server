<?php

namespace App\Api\Lists;

use App\Common\Lists\BaseDataLists;

abstract class BaseApiDataLists extends BaseDataLists
{
    protected array $userInfo = [];
    protected int $userId = 0;

    public string $export;

    public function __construct()
    {
        parent::__construct();

        if ($this->request->attributes->get('userInfo') && $this->request->attributes->get('userId')) {
            $this->userInfo = $this->request->attributes->get('userInfo');
            $this->userId = $this->request->attributes->get('userId');
        }

        $this->export = $this->request->get('export', '');
    }


}
