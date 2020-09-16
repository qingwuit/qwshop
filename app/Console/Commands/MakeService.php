<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\GeneratorCommand;

class MakeService extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:service';

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
        return __DIR__ . '/Stubs/services.stub';
    }

    /**
     * 获取类的默认命名空间
     *
     * @param  string  $rootNamespace
     * @return string
     */

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Services';
    }


}
