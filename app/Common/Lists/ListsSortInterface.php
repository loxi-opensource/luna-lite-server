<?php

namespace App\Common\Lists;

interface ListsSortInterface
{

    /**
     * @notes 设置支持排序字段
     * @return array
     */
    public function setSortFields(): array;

    /**
     * @notes 设置默认排序条件
     * @return array
     */
    public function setDefaultOrder(): array;
}
