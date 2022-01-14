<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class Mysql extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qwshop:mysql';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Artisan::call('migrate:rollback'); // 先清空
        Artisan::call('migrate'); // 迁移
        DB::unprepared(file_get_contents(app_path('Console'.DIRECTORY_SEPARATOR.'Commands'.DIRECTORY_SEPARATOR.'qwshop.sql'))); // 直接执行sql文件 导入数据
    }
}
