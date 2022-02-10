<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentsController extends Controller
{
    protected $modelName = 'OrderComment';
    protected $setUser = true;
    protected $auth = 'users';
    protected $belongName = 'user_id';

    public function store(Request $request)
    {
        $userId = $this->getUserId('users');
        $orderId = request('order_id', 0);
        if (empty($orderId)) {
            return $this->error('not found order');
        }
        $order = $this->getService('Order', true)->find($orderId);
        if ($order->user_id != $userId) {
            return $this->error('not allow');
        }
        if ($order->order_status != 4) {
            return $this->success();
        }
        $orderGoods = $this->getService('OrderGoods', true)->where('order_id', $orderId)->get();
        try {
            DB::beginTransaction();
            foreach ($orderGoods as $v) {
                $this->getService('OrderComment', true)->create([
                    'user_id'   =>  $userId,
                    'order_id'   =>  $order->id,
                    'goods_id'   =>  $v['goods_id'],
                    'store_id'   =>  $order->store_id,
                    'score'   =>  request('score', 5),
                    'agree'   =>  request('agree', 5),
                    'service'   =>  request('service', 5),
                    'speed'   =>  request('speed', 5),
                    'content'   =>  request('content', 5),
                    'image'   =>  empty(request()->image)?'':implode(',', request()->image),
                ]);
            }
            $order->order_status = 6;
            $order->comment_time = now();
            $order->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return $this->error($e->getMessage());
        }
        
        return $this->success();
    }
}
