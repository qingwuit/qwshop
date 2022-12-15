<?php

namespace App\Qingwuit\Traits;

trait ResourceTrait
{
    protected $servicePath = 'App\Qingwuit\Services\\';
    protected $modelPath = 'App\Qingwuit\Models\\';
    protected $jobPath = 'App\Jobs\\';
    protected $resourcePath = 'app\Http\Resources\\';
    protected $resourceNameSpacePath = 'App\Http\Resources\\';

    // 返回服务层地址
    protected function getService($serviceName = 'Users', $isModel = false, $servicePath = null)
    {
        if (!$servicePath) $servicePath = $this->servicePath;
        $serviceName = ucfirst($serviceName); // 大写首字母
        return $isModel ? app()->make($this->modelPath . $serviceName) : app()->make($servicePath . $serviceName . 'Service');
    }

    // 获取任务类
    protected function getJob($jobName = null, $data = [], $iniFun = 'dispatch', $jobPath = null)
    {
        if (!$jobPath) $jobPath = $this->jobPath;
        $jobName = ucfirst($jobName); // 大写首字母
        $fullpath = $this->jobPath . $jobName;
        return $fullpath::$iniFun($data);
    }

    // 是否存在资源文件处理 $type   Collection | Resource
    protected function hasResource($data, $name = '', $type = 0)
    {
        $typeName = 'Collection';
        $ext = '.php';
        if ($type == 1) $typeName = 'Resource';
        $pathName = $this->resourcePath . $name . $typeName . $ext;
        $nameSpace = $this->resourceNameSpacePath . $name . $typeName;
        if (!file_exists(base_path(str_replace('\\', DIRECTORY_SEPARATOR, $pathName)))) return false;
        return new $nameSpace($data);
    }

    // service 返回格式化
    protected function format($data = [], $msg = 'ok')
    {
        return ['status' => true, 'data' => $data, 'msg' => $msg];
    }
    protected function formatError($msg = 'error', $data = [])
    {
        return ['status' => false, 'data' => $data, 'msg' => $msg];
    }


    // Controller 返回格式化

    // 成功返回数据
    protected function success($data = [], $msg = "ok")
    {
        return ['code' => 200, 'msg' => $msg, 'data' => $data];
    }

    // 失败返回数据
    protected function error($msg = "fail", $data = [])
    {
        return ['code' => 500, 'msg' => $msg, 'data' => $data];
    }

    // 自定义返回数据
    protected function auto($code, $msg = "Other", $data = [])
    {
        return ['code' => $code, 'msg' => $msg, 'data' => $data];
    }

    // 快捷返回数据处理
    protected function handle($data)
    {
        return $data['status'] ? $this->success($data['data'], $data['msg']) : $this->error($data['msg'], $data['data']);
    }
}
