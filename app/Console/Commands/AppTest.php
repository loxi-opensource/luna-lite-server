<?php

namespace App\Console\Commands;

use App\Common\Model\Recharge\RechargeOrder;
use Illuminate\Console\Command;

class AppTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '开发测试';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $res = RechargeOrder::query()->orderBy('id', 'desc')->first();
        dd($res->toArray());
    }


}
