<?php

namespace App\Qingwuit\Providers;

use App\Services\ConfigService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Filesystem;
use App\Qingwuit\Providers\AliOssAdapter;
use App\Qingwuit\Providers\Plugins\PutFile;
use App\Qingwuit\Providers\Plugins\PutRemoteFile;
use App\Qingwuit\Services\ConfigsService;
use OSS\OssClient;
use Illuminate\Support\Facades\Log;

class AliOssServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Storage::extend('alioss', function ($app, $config) {
            $configService = new ConfigsService;
            $config = $configService->getFormatConfig('upload');
            $accessId  = $config['key'];
            $accessKey = $config['access'];

            $cdnDomain = empty($config['cdn']) ? '' : $config['cdn'];
            $bucket    = $config['bucket'];
            $ssl       = empty($config['ssl']) ? false : true;
            $isCname   = empty($config['cdn']) ? false : true; // 不是非空全为true不做验证  是否使用域名
            $debug     = env('APP_DEBUG');

            $endPoint  = $config['endpoint']; // 默认作为外部节点
            $epInternal = !empty($config['endpoint_internal']) ? $config['endpoint_internal'] : $endPoint; // 这一段其实没什么用，直接调节点就行
            $epInternal = $isCname ? $cdnDomain : $epInternal; // 内部节点
            
            $client  = new OssClient($accessId, $accessKey, $epInternal, $isCname);
            $adapter = new AliOssAdapter($client, $bucket, $endPoint, $ssl, $isCname, $debug, $cdnDomain);
            
            $filesystem =  new Filesystem($adapter);
            
            $filesystem->addPlugin(new PutFile());
            $filesystem->addPlugin(new PutRemoteFile());
            //$filesystem->addPlugin(new CallBack());
            return $filesystem;
        });
    }
}
