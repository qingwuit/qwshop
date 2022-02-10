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
     * @return int
     */
    public function handle()
    {

        // 判断是否解开禁用函数
        $disable_functions = ['shell_exec','exec','symlink','proc_open','putenv'];
        $disabled = false;
        for ($i=0;$i<count($disable_functions);$i++) {
            if (!function_exists($disable_functions[$i])) {
                $disabled = true;
                break;
            }
        }
        
        if ($disabled) {
            $this->line('');
            $this->error('disabled_functions error. Please go php.ini Delete [shell_exec|exec|symlink|proc_open|putenv]...');
            $this->line('');
            return;
        }
        
        $domain = $this->anticipate('Please enter your Domain name (https://xxx.com)', ['http://127.0.0.1', 'localhost']);
        
        if (stripos($domain, 'https://')===false && stripos($domain, 'http://')===false) {
            $this->error('Domain name error. Please fill in again...');
            return;
        }

        $mysqlHost = $this->anticipate('Please enter your MySQL address (Host)', ['127.0.0.1', 'localhost']);
        $dbPort = $this->anticipate('Please enter your MySQL port ', ['3306']);
        $dbName = $this->anticipate('Please enter your MySQL DBName ', ['qwshop', 'shop']);
        $dbUserName = $this->anticipate('Please enter your MySQL DBusername ', ['root','qwshop']);
        $dbPassword = $this->anticipate('Please enter your MySQL DBpassword ', ['root','qwshop']);

        // 设置成功Mysql
        $this->table(['domain','host','prot','dbname','username','password'], [[$domain,$mysqlHost,$dbPort,$dbName,$dbUserName,$dbPassword]]);
        $this->line('');
        if (empty($domain) || empty($mysqlHost) || empty($dbPort) || empty($dbName) || empty($dbUserName)) {
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

        // 这里得加下缓存，等数据执行完再清除缓存

        $bar = $this->output->createProgressBar(3);

        // 执行migrate
        $bar->advance(); // 第一步
        $this->line('');
        $this->line('');
        $this->line('Data migration. Please wait...');
        Artisan::call('config:clear'); // 清空缓冲
        Artisan::call('config:cache'); // 继续缓冲
        Artisan::call('migrate'); // 原本想使用这个太麻烦
        $this->line('');
        $this->line('Creating table. Please wait...');
        Artisan::call('qwshop:mysql'); // 导入数据结构
        $this->line('');
        $this->info('Database imported successfully.');
        
        // 创建软链接链接storage
        $bar->advance(); // 第二步
        Artisan::call('storage:link');
        Artisan::call('config:clear'); // 清空缓冲
        $this->line('');
        $this->line('');
        $this->info('Create soft link link storage successfully.');
        $bar->finish();

      
        $this->line('');
        $this->line('');
        $this->info('Install Successfully , Welcome Qwshop.');
        $this->line('');
        $this->line('Admin url :  '.$domain.'/Admin/login');
        $this->line('Seller url :  '.$domain.'/Seller/login');
        $this->line('Admin username :  admin');
        $this->line('Admin password :  123456');
        return;
    }

    // 修改env
    public function modifyEnv(array $data)
    {
        $envPath = base_path()  . DIRECTORY_SEPARATOR . '.env';
        $contentArray = collect(file($envPath, FILE_IGNORE_NEW_LINES));
        
        $contentArray->transform(function ($item) use ($data) {
            foreach ($data as $key => $value) {
                if (str_contains($item, $key)) {
                    return $key . '=' . $value;
                }
            }
            return $item;
        });

        // 修改.env数据
        $content = implode(PHP_EOL, $contentArray->toArray());
        file_put_contents($envPath, $content);
    }
}
