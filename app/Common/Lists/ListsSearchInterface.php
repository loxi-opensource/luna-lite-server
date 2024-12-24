<?php

namespace App\Common\Lists;

interface ListsSearchInterface
{
    /**
     * @notes 设置搜索条件
     * @return array
     */
    public function setSearch(): array;
}
