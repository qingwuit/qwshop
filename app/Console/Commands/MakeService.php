<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\GeneratorCommand; // 重点

class MakeService extends GeneratorCommand // 重点需要注意的地方，之前是继承的Command 这里记得改下
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:service'; // 重点需要注意的地方，之前是$signature这里记得改下 改成$name

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service class';

    /**
     * 生成类的类型
     *
     * @var string
     */

    protected $type = 'Services';

    /**
     * 获取生成器的存根文件
     *
     * @return string
     */

    protected function getStub()
    {
        return __DIR__ . '/Stubs/service.stub';
    }

    /**
     * 获取类的默认命名空间
     *
     * @param  string  $rootNamespace
     * @return string
     */

    protected function getDefaultNamespace($rootNamespace)
    {
        $path = app_path('Services');
        if (!is_dir(app_path('Services'))) {
            mkdir($path, 0777, true);
        }
        return $rootNamespace . '\Services';
    }
}
