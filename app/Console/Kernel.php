<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Crontab\Crontab;

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
        // 统计数据 定时任务
        $schedule->call(function () {
            $crontabl_model = new Crontab();
            $crontabl_model->crontab_day();
        })->everyMinute(); // daily

        $schedule->call(function () {
            $crontabl_model = new Crontab();
            $crontabl_model->crontab_week();
        })->weekly();

        $schedule->call(function () {
            $crontabl_model = new Crontab();
            $crontabl_model->crontab_month();
        })->monthly();


        // 这是定时 订单处理
        $schedule->call(function () {
            $crontabl_model = new Crontab();
            $crontabl_model->complete_order();  // 确定订单
            $crontabl_model->comment_order(); // 评论订单
            $crontabl_model->cancel_order(); // 取消订单
        })->everyMinute(); // daily
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
