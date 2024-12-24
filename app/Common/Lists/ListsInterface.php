<?php

namespace App\Common\Lists;


interface ListsInterface
{
    /**
     * @notes 实现数据列表
     * @return array
     */
    public function lists(): array;

    /**
     * @notes 实现数据列表记录数
     * @return int
     */
    public function count(): int;

}
