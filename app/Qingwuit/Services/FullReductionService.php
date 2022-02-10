<?php
namespace App\Qingwuit\Services;

class FullReductionService extends BaseService
{
    public function fullReductionInfoByStoreId($storeId, $total_price)
    {
        $info = $this->getService('FullReduction', true)->where('store_id', $storeId)->where('start_time', '<', now())->where('end_time', '>', now())->where('use_money', '<=', $total_price)->orderBy('use_money', 'asc')->first();
        if (!$info) {
            return $this->formatError('not found');
        }
        return $this->format($info);
    }
}
