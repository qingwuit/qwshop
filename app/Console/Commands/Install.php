<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

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
        
        $domain = $this->anticipate('Please enter your Domain name (https://xxx.com)', ['http://127.0.0.1', 'localhost']);
        $mysqlHost = $this->anticipate('Please enter your MySQL address (Host)', ['127.0.0.1', 'localhost']);
        $dbPort = $this->anticipate('Please enter your MySQL port ', ['3306']);
        $dbName = $this->anticipate('Please enter your MySQL DBName ', ['qwshop', 'shop']);
        $dbUserName = $this->anticipate('Please enter your MySQL DBusername ', ['root']);
        $dbPassword = $this->anticipate('Please enter your MySQL DBpassword ', ['root']);

        // 设置成功Mysql
        $this->table(['domain','host','prot','dbname','username','password'], [[$domain,$mysqlHost,$dbPort,$dbName,$dbUserName,$dbPassword]]);
        $this->line('');
        if(empty($domain) || empty($mysqlHost) || empty($dbPort) || empty($dbName) || empty($dbUserName)){
            return $this->error('Setting Error.');
        }

        // 开始修改.env 数据
        $this->modifyEnv([
            'APP_URL'       =>  $domain,
            'APP_DEBUG'     =>  'false',
            'DB_HOST'       =>  $mysqlHost,
            'DB_PORT'       =>  $dbPort,
            'DB_DATABASE'   =>  $dbName,
            'DB_USERNAME'   =>  $dbUserName,
            'DB_PASSWORD'   =>  $dbPassword,
        ]);

        $bar = $this->output->createProgressBar(3);

        // 执行migrate
        $bar->advance(); // 第一步
        $this->line('');
        $this->line('');
        $this->line('Creating table. Please wait...');
        Artisan::call('config:cache');
        Artisan::call('migrate'); // 原本想使用这个太麻烦
        Artisan::call('qwshop:mysql'); // 导入数据结构
        $this->line('');
        $this->info('Database imported successfully.');
        

        // 执行seeder
        // Artisan::call('db:seed --class=AdminSeeder');
        // Artisan::call('db:seed --class=ConfigSeeder');
        // Artisan::call('db:seed --class=MenuSeeder');
        // $bar->advance(); // 第二步

        // 创建软链接链接storage
        $bar->advance(); // 第二步
        Artisan::call('storage:link');
        $this->line('');
        $this->line('');
        $this->info('Create soft link link storage successfully.');

        // 修改前端接口链接
        $bar->advance(); // 第三步
        Artisan::call('qwshop:vue '.$domain);
        $this->line('');
        $this->line('');
        $this->info('Modify front end interface link successfully.');

        $bar->finish();

      
        $this->line('');
        $this->line('');
        $this->info('Install Successfully , Welcome Qwshop.');
        $this->line('');
        $this->line('Admin url :  /Admin/login');
        $this->line('Seller url :  /Seller/login');
        $this->line('Admin username :  admin');
        $this->line('Admin password :  123456');
        return;;
        
    }

    // 修改env
    function modifyEnv(array $data){
        $envPath = base_path()  . DIRECTORY_SEPARATOR . '.env';
        $contentArray = collect(file($envPath, FILE_IGNORE_NEW_LINES));
        
        $contentArray->transform(function ($item) use ($data){
            foreach ($data as $key => $value){
                if(str_contains($item, $key)){
                return $key . '=' . $value;
                }
            }
            return $item;
        });

        // 修改.env数据
        $content = implode(PHP_EOL,$contentArray->toArray());
        file_put_contents($envPath, $content);
        
    }
}
