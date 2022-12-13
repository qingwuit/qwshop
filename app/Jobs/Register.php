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
     *
     * @return void
     */
    public function handle()
    {
        // $step = $this->data['step']??false;
        $data = $this->data;
        $model = $this->getService($data['model_name'],true);
        // 判断是否存在相同得账号和电话
        if ($model->where($data['type'],$data['reg_data']['username'])->exists()) {
            // 该账号已经存在
            return info('['.$data['reg_data']['username'].'] '.__('tip.userExist'));
        }
        if (!$model->create($data['reg_data'])) return info('['.$data['reg_data']['username'].'] register error');;
    }
}
