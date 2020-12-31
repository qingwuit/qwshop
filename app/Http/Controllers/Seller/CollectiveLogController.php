<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Resources\Seller\CollectiveLogResourcee\CollectiveLogCollection;
use App\Models\CollectiveLog;
use Illuminate\Http\Request;

class CollectiveLogController extends Controller
{
    public function index(Request $request,CollectiveLog $collective_log_model)
    {
        $list = $collective_log_model->with(['goods'=>function($q){
            $q->select('id','goods_name','goods_master_image');
        }])->withCount('orders')->where('store_id',$this->get_store(true))->where('collective_id',$request->collective_id)->orderBy('id','desc')->paginate($request->per_page??30);
        return $this->success(new CollectiveLogCollection($list));
    }
}
