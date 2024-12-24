<?php

namespace App\Common\Model\File;

use App\Common\Model\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends BaseModel
{
    protected $table = 'file';

    use SoftDeletes;

    protected function getDeletedAtColumn()
    {
        return 'delete_time';
    }

}
