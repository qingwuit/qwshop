<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qwshop:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Qwshop Install Command Start';

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
        
        $mysqlHost = $this->anticipate('Please enter your MySQL address (Host)', ['127.0.0.1', 'localhost']);
        $dbPort = $this->anticipate('Please enter your MySQL port ', ['3306']);
        $dbName = $this->anticipate('Please enter your MySQL DBName ', ['qwshop', 'shop']);
        $dbUserName = $this->anticipate('Please enter your MySQL DBusername ', ['root']);
        $dbPassword = $this->anticipate('Please enter your MySQL DBpassword ', ['root']);

        // 设置成功Mysql
        $this->table(['host','prot','dbname','username','password'], [[$mysqlHost,$dbPort,$dbName,$dbUserName,$dbPassword]]);
        $this->line('');
        if(empty($mysqlHost) || empty($dbPort) || empty($dbName) || empty($dbUserName)){
            return $this->error('Setting Mysql Error.');
        }
        $bar = $this->output->createProgressBar(3);

        // 执行migrate
        Artisan::call('migrate');
        $bar->advance(); // 第一步

        // 执行seeder
        Artisan::call('db:seed --class=AdminSeeder');
        Artisan::call('db:seed --class=ConfigSeeder');
        Artisan::call('db:seed --class=MenuSeeder');
        $bar->advance(); // 第二步

        // 创建软链接链接storage
        Artisan::call('storage:link');
        $bar->advance(); // 第三步
        $bar->finish();

        $this->line('');
        $this->line('');
        return $this->info('Install Success , Welcome Qwshop.');
        
    }
}
