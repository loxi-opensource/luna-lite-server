<?php

namespace App\Common\Model;

use Illuminate\Support\Facades\Config;

class OperationLog extends BaseModel
{
    protected $table = 'operation_log';

    public $casts = [
        'create_time' => 'datetime:Y-m-d H:i:s',
    ];

    protected $dateFormat = 'U';

    public $timestamps = false;

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->timezone(Config::get('app.timezone'))->format('Y-m-d H:i:s');
    }
}
