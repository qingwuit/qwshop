<?php

namespace App\Console;

use App\Qingwuit\Services\AutoService;
use App\Qingwuit\Services\ConfigsService;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        // 定时清理失效和撤销的令牌
        $schedule->command('passport:purge')->hourly();

        $config_service = new ConfigsService();
        $auto_service = new AutoService();

        // 这是定时 订单处理
        $schedule->call(function () use ($auto_service) {
            $auto_service->autoTask();  // 确定订单
        })->everyMinute()->name('order')->withoutOverlapping();

        // 订单结算
        $task = $config_service->getFormatConfig('task');
        $schedule->call(function () use ($auto_service) {
            $auto_service->orderSettlement();
        })->cron('0 0 */'.$task['settlement'].' * *')->name('settlement')->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
