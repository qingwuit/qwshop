<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // 不使用 Passport 默认的迁移
        Passport::ignoreMigrations();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if(env('URL_SECURE')) URL::forceScheme('https');
        // 全局打印Sql
        // DB::listen(
        //     function ($sql) {
        //         foreach ($sql->bindings as $i => $binding) {
        //             if ($binding instanceof \DateTime) {
        //                 $sql->bindings[$i] = $binding->format('\'Y-m-d H:i:s\'');
        //             } else {
        //                 if (is_string($binding)) {
        //                     $sql->bindings[$i] = "'$binding'";
        //                 }
        //             }
        //         }

        //         // Insert bindings into query
        //         $query = str_replace(array('%', '?'), array('%%', '%s'), $sql->sql);

        //         $query = vsprintf($query, $sql->bindings);

        //         // Save the query to file
        //         $logFile = fopen(
        //             storage_path('logs' . DIRECTORY_SEPARATOR . date('Y-m-d') . '_query.log'),
        //             'a+'
        //         );
        //         fwrite($logFile, date('Y-m-d H:i:s') . ': ' . $query . PHP_EOL);
        //         fclose($logFile);
        //     }
        // );
    }
}
