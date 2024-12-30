<?php

namespace App\Console\Commands;

use App\Common\Model\SwapTemplateGroup;
use App\Common\Service\FaceSwap\FaceSwapService;
use Illuminate\Console\Command;
use Illuminate\Database\Query\Builder;

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
        $rows = SwapTemplateGroup::query()
            ->with(['templates' => fn($query) => $query->where('status', 1)])
            ->get();
        dd($rows->toArray());
    }

}
