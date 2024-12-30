<?php

namespace App\Console\Commands;

use App\Common\Model\DigitalAvatar;
use App\Common\Service\ConfigService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

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
        $res = DigitalAvatar::query()->where('id', '>', 0)->get();
        dd($res->toArray());
    }

}
