<?php
namespace App\Qingwuit\Services;

class SeckillService extends BaseService
{
    public function seckillInfoByGoodsId($goodsId = 0)
    {
        $info = $this->getService('Seckill', true)->select('discount', 'start_time', 'end_time')->where('goods_id', $goodsId)->where('start_time', '<=', now())->where('end_time', '>=', now())->first();
        if (!$info) {
            return $this->formatError('not found');
        }
        return $this->format($info);
    }
}
