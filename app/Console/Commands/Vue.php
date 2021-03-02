<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Vue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qwshop:vue {host=http://127.0.0.1:8000}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'edit vue baseUrl';

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
        $url = $this->argument('host');
        $path = resource_path('js/plugins/apis/common.js');
        $content = file_get_contents($path);
        $content = preg_replace("/\'.*\'/i","'".$url.'/api/'."'",$content);
        file_put_contents($path,$content);
    }
}
