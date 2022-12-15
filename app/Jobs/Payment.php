<?php

namespace App\Jobs;

use App\Qingwuit\Traits\ResourceTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class Payment implements ShouldQueue
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
     * $data['payment_name'] 支付方式
     * $data['device'] 设备
     * $data['config'] 配置
     * $data['out_trade_no'] 第三方单号
     * $data['result'] 回调数据
     *
     * @return void
     */
    public function handle()
    {
        $data = $this->data;
        try {
            DB::beginTransaction();
            $paymentService = $this->getService('Payment');
            $paymentService->setConfig($data['payment_name'], $data['device'], $data['config']);
            $orderPay = $this->getService('OrderPay', true)->where('pay_no', $data['out_trade_no'])->first();
            $paySuccessData = $paymentService->paySuccess($data['payment_name'], $orderPay, $data['result']);
            if (!$paySuccessData['status']) throw new \Exception($paySuccessData['msg']);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            info('REDIS回调报错：' . $e->getMessage());
        }
    }
}
