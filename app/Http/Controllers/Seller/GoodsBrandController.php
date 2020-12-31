<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Resources\Seller\GoodsBrandResource\GoodsBrandCollection;
use App\Models\GoodsBrand;
use Illuminate\Http\Request;

class GoodsBrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,GoodsBrand $goods_brand_model)
    {
        if(!empty($request->name)){
            $goods_brand_model = $goods_brand_model->where('name','like','%'.$request->name.'%');
        }
        $list = $goods_brand_model->orderBy('id','desc')->paginate($request->per_page??30);
        return $this->success(new GoodsBrandCollection($list));
    }

    
}
