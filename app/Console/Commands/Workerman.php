<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Workerman\Worker;
use Workerman\Connection\TcpConnection;
use Workerman\Timer;

class Workerman extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'workerman {cmd} {--d} {--g}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start a Workerman server.';

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
        global $argv;
        $cmd[] = $this->argument('cmd');
        if($this->option('d')) $cmd[] = '-d';
        if($this->option('g')) $cmd[] = '-g';
        $argv = $cmd;
        $this->start();
    }

    /**
     * 发送结构
     *
     * @return void
     * @Description
     * @Author hg <bishashiwo@gmail.com>
     * @Time 2022-02-20
     * [
     *    type=>'chat', // heart
     *    data=>[
     *      'type'=>'text',
     *      'sid'=>'xxx',
     *      'rid'=>'xxx',
     *      'data'=>[],
     *    ]
     * ]
     */
    protected function start()
    {
        // // 证书最好是申请的证书
        // $context = array(
        //     // 更多ssl选项请参考手册 http://php.net/manual/zh/context.ssl.php
        //     'ssl' => array(
        //         // 请使用绝对路径
        //         'local_cert'        => '磁盘路径/server.pem', // 也可以是crt文件
        //         'local_pk'          => '磁盘路径/server.key',
        //         'verify_peer'       => false,
        //         'allow_self_signed' => true, //如果是自签名证书需要开启此选项
        //     )
        // );
        // // 这里设置的是websocket协议（端口任意，但是需要保证没被其它程序占用）
        // $ws_worker = new Worker('websocket://0.0.0.0:2000', $context);
        // $ws_worker->transport = 'ssl';
        //
        // // nginx 代理使用
        // location /wss
        // {
        //     proxy_pass http://127.0.0.1:2000;
        //     proxy_http_version 1.1;
        //     proxy_set_header Upgrade $http_upgrade;
        //     proxy_set_header Connection "Upgrade";
        //     proxy_set_header X-Real-IP $remote_addr;
        // }
        global $ws_worker;
        $ws_worker = new Worker("websocket://0.0.0.0:2000");
        $ws_worker->count = 1;
        $ws_worker->uidConnections = [];
        // 当收到客户端发来的数据后返回hello $data给客户端
        $ws_worker->onMessage = function (TcpConnection $connection, $data) {
            global $ws_worker;
            try {
                $resp = json_decode($data, true);

                $respData = $resp['data'];

                // 发送者未存储Id
                if (!isset($connection->uid)) {
                    $connection->uid = $respData['sid'];
                    $ws_worker->uidConnections[$connection->uid] = $connection;
                }

                // 如果是心跳则不继续执行
                if ($resp['type'] == 'heart') $ws_worker->uidConnections[$respData['sid']]->send($data);
                if ($resp['type'] == 'heart') return $ws_worker->uidConnections[$connection->uid]->lastMessageTime = time();

                $ws_worker->uidConnections[$connection->uid]->lastMessageTime = time(); // 每次聊天都更新时间

                // 接收者无存储ID
                if (isset($ws_worker->uidConnections[$respData['rid']])) {
                    $ws_worker->uidConnections[$respData['rid']]->send($data);
                }
            } catch (\Exception $e) {
                var_dump($e->getMessage()); // 打印下错误信息
            }
        };

        // 断开连接
        $ws_worker->onClose = function (TcpConnection $connection) {
            global $ws_worker;
            if (isset($connection->uid)) {
                // 连接断开时删除映射
                unset($ws_worker->uidConnections[$connection->uid]);
            }
        };

        // 进程启动后设置一个每10秒运行一次的定时器 心跳检查
        $ws_worker->onWorkerStart = function ($ws_worker) {
            // 开启一个内部端口，方便内部系统推送数据，Text协议格式 文本+换行符
            $inner_text_worker = new Worker('text://0.0.0.0:2001');
            $inner_text_worker->onMessage = function(TcpConnection $connection, $buffer) use($ws_worker)
            {
                // $data数组格式，里面有uid，表示向那个uid的页面推送数据
                $innerData = json_decode($buffer, true);
                $uid = $innerData['data']['rid'];
                // 通过workerman，向uid的页面推送数据
                if(!isset($ws_worker->uidConnections[$uid])) return;
                $ws_worker->uidConnections[$uid]->send($buffer);
            };
            // ## 执行监听 ##
            $inner_text_worker->listen();


            Timer::add(10, function () use ($ws_worker) {
                $time_now = time();
                foreach ($ws_worker->uidConnections as $connection) {
                    // 有可能该connection还没收到过消息，则lastMessageTime设置为当前时间
                    if (empty($connection->lastMessageTime)) {
                        $connection->lastMessageTime = $time_now;
                        continue;
                    }
                    // 上次通讯时间间隔大于心跳间隔，则认为客户端已经下线，关闭连接
                    if ($time_now - $connection->lastMessageTime > 55) {
                        $connection->close();
                    }
                }
            });
        };

        // 运行worker
        Worker::runAll();
    }

    protected function stop()
    {
        Worker::stopAll();
    }
}
