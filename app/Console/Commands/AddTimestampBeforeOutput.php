<?php

namespace App\Console\Commands;

trait AddTimestampBeforeOutput
{
    public function line($string, $style = null, $verbosity = null)
    {
        $string = now()->format("[Y-m-d H:i:s] ") . $string;
        return parent::line($string, $style, $verbosity);
    }
}
