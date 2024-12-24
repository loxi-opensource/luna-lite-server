<?php

namespace App\Common\Lists;

interface ListsExcelInterface
{

    /**
     * @notes 设置导出字段
     * @return array
     */
    public function setExcelFields(): array;


    /**
     * @notes 设置导出文件名
     * @return string
     */
    public function setFileName(): string;

}
