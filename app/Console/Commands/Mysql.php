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
        Artisan::call('migrate:reset'); // 先清空
        Artisan::call('migrate'); // 迁移
        // // 例1 导入sql有点慢试试这个新办法 没试过不知可行不可行，暂时先注释 ChatGPT3.5 给出的优化方案
        // DB::disconnect();
        // $systemCmd = 'mysql -u' . env('DB_USERNAME') . ' -p' . env('DB_PASSWORD') . ' -h '.env('DB_HOST').' -P '.env('DB_PORT').' ' . env('DB_DATABASE') . ' < ' . app_path('Console' . DIRECTORY_SEPARATOR . 'Commands' . DIRECTORY_SEPARATOR . 'qwshop.sql');
        // system($systemCmd);
        // // 例2
        // DB::unprepared('system mysql -u' . env('DB_USERNAME') . ' -p' . env('DB_PASSWORD') . ' -h '.env('DB_HOST').' -P '.env('DB_PORT').' ' . env('DB_DATABASE') . ' < ' . app_path('Console' . DIRECTORY_SEPARATOR . 'Commands' . DIRECTORY_SEPARATOR . 'qwshop.sql'));
        DB::unprepared(file_get_contents(app_path('Console' . DIRECTORY_SEPARATOR . 'Commands' . DIRECTORY_SEPARATOR . 'qwshop.sql'))); // 直接执行sql文件 导入数据
    }
}
