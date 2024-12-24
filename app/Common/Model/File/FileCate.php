<?php

namespace App\Common\Model\File;

use App\Common\Model\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class FileCate extends BaseModel
{
    protected $table = 'file_cate';

    use SoftDeletes;

    protected function getDeletedAtColumn()
    {
        return 'delete_time';
    }

}
