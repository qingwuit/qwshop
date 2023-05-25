<?php

namespace App\Jobs;

use App\Qingwuit\Traits\ResourceTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class Register implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, ResourceTrait;

    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     * $data['model_name'] 模型
     * $data['register_type'] 注册类型 phone username
     * $data['reg_data] 注册插入数据库数据
     * @return void
     */
    public function handle()
    {
        // $step = $this->data['step']??false;
        $data = $this->data;
        $model = $this->getService($data['model_name'], true);
        // 判断是否存在相同得账号和电话
        if ($model->where($data['register_type'], $data['reg_data']['username'])->exists()) {
            // 该账号已经存在
            return info('[' . $data['reg_data']['username'] . '] ' . __('tip.userExist'));
        }
        if (!$model->create($data['reg_data'])) return info('[' . $data['reg_data']['username'] . '] register error');
    }
}
