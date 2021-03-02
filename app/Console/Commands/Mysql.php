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
    protected $signature = 'qwshop:mysql {name=insert}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'sql handle restart:drop db';

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
     * @return mixed
     */
    public function handle()
    {
        $name = $this->argument('name');
        if($name == 'insert'){
            // 执行seeder 清空数据
            Artisan::call('db:seed --class=DatabaseSeeder');
            DB::unprepared(file_get_contents(app_path('Console'.DIRECTORY_SEPARATOR.'Commands'.DIRECTORY_SEPARATOR.'qwshop.sql'))); // 直接执行sql文件 导入数据
        }
    }
}
