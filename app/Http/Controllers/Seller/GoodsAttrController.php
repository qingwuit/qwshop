<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Resources\Seller\GoodsAttrResource\GoodsAttrCollection;
use App\Models\GoodsAttr;
use App\Models\GoodsSku;
use App\Models\GoodsSpec;
use App\Services\UserService;
use Illuminate\Http\Request;

class GoodsAttrController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,GoodsAttr $goods_attr_model)
    {
        $list = $goods_attr_model->where('store_id',$this->get_store(true))->with('specs')->orderBy('id','desc')->paginate($request->per_page??30);
        return $this->success(new GoodsAttrCollection($list));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,GoodsAttr $goods_attr_model)
    {
        $goods_attr_model = $goods_attr_model->create([
                                'store_id'=>$this->get_store(true),
                                'name'=>$request->name??''
                            ]);
        if(!empty($request->spec_name)){
            $spec_list = [];
            foreach($request->spec_name as $v){
                $spec_list[] = ['name'=>$v];
            }
            $goods_attr_model->specs()->createMany($spec_list);
        }
        
        return $this->success([],__('base.success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(GoodsAttr $goods_attr_model,$id)
    {
        $info = $goods_attr_model->with('specs')->find($id);
        return $this->success($info);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,GoodsAttr $goods_attr_model,GoodsSpec $goods_spec_model,GoodsSku $goods_sku_model, $id)
    {
        $goods_attr_model = $goods_attr_model->find($id);
        $goods_attr_model->name = $request->name;
        $goods_attr_model->store_id = $this->get_store(true);
        $not_spec = $goods_spec_model->where('attr_id',$id)->whereNotIn('name',$request->spec_name)->get();

        $spec_id = [];

        if(!empty($not_spec)){
            foreach($not_spec as $v){
                $spec_id[] = $v['id'];
            }
        }

        if(!empty($spec_id)){
            if($goods_sku_model->whereIn('spec_id',$spec_id)->exists()){
                return $this->error(__('admins.goods_attr_delete'));
            }
        }

        if(!empty($request->spec_name)){
            foreach($request->spec_name as $v){
                if(!$goods_spec_model->where(['attr_id'=>$id,'name'=>$v])->exists()){
                    $goods_spec_model->create(['name'=>$v,'attr_id'=>$id]);
                }
            }
        }
        
        $goods_attr_model->save();
        $goods_spec_model->whereIn('id',$spec_id)->delete();
        return $this->success([],__('base.success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(GoodsAttr $goods_attr_model,GoodsSpec $goods_spec_model,GoodsSku $goods_sku_model,$id)
    {
        $idArray = array_filter(explode(',',$id),function($item){
            return is_numeric($item);
        });
        if($goods_sku_model->whereIn('attr_id',$idArray)->exists()){
            return $this->error(__('admins.goods_attr_delete'));
        }
        $goods_spec_model->whereIn('attr_id',$idArray)->delete();
        $goods_attr_model->destroy($idArray);
        return $this->success([],__('base.success'));
    }
}
