<?php

namespace App\Jobs;

use App\Qingwuit\Traits\ResourceTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateOrder implements ShouldQueue
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
        $data = $this->data;
        try {
            $orderService = $this->getService('Order');
            $createData = $orderService->createOrderData(
                $data['rs'],
                $data['address_resp'],
                $data['coupon_id'],
                $data['userId'],
                $data['make_rands'],
                $data['remark'],
            );
            if (!$createData['status']) throw new \Exception($createData['msg']);
        } catch (\Exception $e) {
            info('REDIS创建订单报错：' . $e->getMessage());
        }
    }
}
