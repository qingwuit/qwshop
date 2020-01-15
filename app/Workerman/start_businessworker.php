<?php 
/**
 * This file is part of workerman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link http://www.workerman.net/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Workerman;
use GatewayWorker\BusinessWorker;
use Workerman\Worker;
require_once __DIR__ . '/../../vendor/autoload.php';
$worker                  = new BusinessWorker();
$worker->name            = 'BusinessWorker';
$worker->count           = 1;
$worker->registerAddress = '127.0.0.1:1236';
$worker->eventHandler    = \App\Workerman\Events::class;


// 如果不是在根目录启动，则运行runAll方法
if(!defined('GLOBAL_START'))
{
    Worker::runAll();
}

