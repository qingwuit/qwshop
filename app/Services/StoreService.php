<?php
namespace App\Services;

use App\Http\Resources\Home\StoreResource\StoreCollection;
use App\Http\Resources\Home\StoreResource\StoreJoin;
use App\Models\OrderComment;
use App\Models\Store;
use App\Models\StoreClass;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StoreService extends BaseService{

    // 获取店铺信息
    public function getHomeStoreList(){
        $store_model = new Store;
        $params = request()->params??'';
        $lat = request()->lat??'39.56';
        $lng = request()->lng??'116.20'; // 默认北京的经纬度

        $distance = "ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN(('$lat' * PI() / 180 - store_lat * PI() / 180) / 2),2) + COS(40.0497810000 * PI() / 180) * COS(store_lat * PI() / 180) * POW(SIN(('$lng' * PI() / 180 - store_lng * PI() / 180) / 2),2))) * 1000 )  AS distance ";

        $store_model = $store_model->select(DB::raw('*,'.$distance))->withCount(['comments','comments as good_comment'=>function($q){
            $q->whereRaw('(score+agree+speed+service)>=15');
        }]);

        try{
            if(!empty($params)){
                $params_array = json_decode(base64_decode($params),true);
                // 排序
                if(isset($params_array['sort_type']) && !empty($params_array['sort_type'])){
                    $store_model = $store_model->orderBy($params_array['sort_type'],$params_array['sort_order']);
                }else{
                    $store_model = $store_model->orderBy('distance','desc')->orderBy('id','desc');
                }
            }
            $list = $store_model->paginate(request()->per_page??30);
            return $this->format(new StoreCollection($list) );
        }catch(\Exception $e){
            return $this->format_error($e->getMessage());
        }
    }

    // 入驻时获取店铺状态
    public function getStoreVerify($auth='user'){
        $store_model = new Store();
        $store_info = $store_model->where('user_id',auth($auth)->id())->first();
        if(empty($store_info)){
            return $this->format_error(__('stores.store_not_defined'));
        }
        return $this->format(new StoreJoin($store_info));
        
    }

    // 登录用户获取店铺信息
    public function getAuthStoreInfo($where=""){
        $store_info = $this->get_store(false,$where);
        if(empty($store_info)){
            return $this->format_error(__('stores.store_not_defined'));
        }

        // 地址处理
        $store_info['area_id'] = [$store_info['province_id'],$store_info['city_id'],$store_info['region_id']];

        // 店铺分类
        $choseStoreClasses = $this->getStoreGoodsClasses($store_info['id'])['data'];
        $store_info['chose_store_classes'] = $choseStoreClasses;
        return $this->format($store_info);
    }

    // 获取店铺信息
    public function getStoreInfo($store_id,$select=""){
        $stores_model = new Store();
        if(!empty($select)){
            $stores_model = $stores_model->select(DB::raw($select));
        }
        $store_info = $stores_model->find($store_id);
        if(empty($store_info)){
            return $this->format_error(__('stores.store_not_defined'));
        }
        return $this->format($store_info);
    }

    // 获取店铺信息和评分信息
    public function getStoreInfoAndRate($store_id,$select=""){
        $oc = new OrderComment();
        $store_info = $this->getStoreInfo($store_id,$select)['data'];
        $info = $oc->where('store_id',$store_id)->first([
            DB::raw('avg(score) as scoreAll'),
            DB::raw('avg(agree) as agreeAll'),
            DB::raw('avg(service) as serviceAll'),
            DB::raw('avg(speed) as speedAll'),
        ])->toArray();
        foreach($info as &$v){
            $v = intval($v);
        }
        $store_info['rate'] = $info;
        return $this->format($store_info);
    }

    // 获取商家拥有的栏目信息
    public function getStoreGoodsClasses($store_id){
        // 店铺分类
        $store_classes_model = new StoreClass();
        $store_classes = $store_classes_model->where('store_id',$store_id)->first();
        $class_id = json_decode($store_classes['class_id'],true);
        $class_name = json_decode($store_classes['class_name'],true);
        $choseStoreClasses = [];
        foreach($class_id as $k=>$v){
            $choseStoreClasses[$k] = [];
            foreach($v as $key=>$vo){
                $choseStoreClasses[$k][$key]['id'] = $vo;
                $choseStoreClasses[$k][$key]['name'] = $class_name[$k][$key];
            }
        }
        return $this->format($choseStoreClasses);
    }

    // 建立店铺
    public function createStore($auth='user'){
        $store_model = new Store();
        $user_id = auth($auth)->id();
        if($store_model->where('user_id',$user_id)->exists()){
            return $this->format_error(__('stores.store_defined'));
        }
        $store_model->user_id = $user_id;
        $store_model->store_verify = 1;
        $store_model->store_status = 0;
        $rs = $store_model->save();
        return $this->format($rs,__('base.success'));
    }

    // 编辑店铺
    public function editStore($store_id){
        $store_model = new Store();
        $store_model = $store_model->find($store_id);

        // 店铺名
        if(isset(request()->store_name)){
            $store_model->store_name = request()->store_name;
        }
        // 店铺LOGO
        if(isset(request()->store_logo)){
            $store_model->store_logo = request()->store_logo;
        }
        // 店铺门面
        if(isset(request()->store_face_image)){
            $store_model->store_face_image = request()->store_face_image;
        }
        // 店铺幻灯片
        if(isset(request()->store_slide)){
            $store_model->store_slide = request()->store_slide;
        }
        // 店铺手机幻灯片
        if(isset(request()->store_mobile_slide)){
            $store_model->store_slide = request()->store_mobile_slide;
        }
        // 店铺电话
        if(isset(request()->store_mobile)){
            $store_model->store_mobile = request()->store_mobile;
        }
        // 店铺描述
        if(isset(request()->store_description)){
            $store_model->store_description = request()->store_description;
        }
        // 公司名称
        if(isset(request()->store_company_name)){
            $store_model->store_company_name = request()->store_company_name;
        }
        // 省ID
        if(isset(request()->province_id)){
            $store_model->province_id = request()->province_id;
        }
        // 市ID
        if(isset(request()->city_id)){
            $store_model->city_id = request()->city_id;
        }
        // 区ID
        if(isset(request()->region_id)){
            $store_model->region_id = request()->region_id;
        }
        // 纬度
        if(isset(request()->store_lat)){
            $store_model->store_lat = request()->store_lat;
        }
        // 经度
        if(isset(request()->store_lng)){
            $store_model->store_lng = request()->store_lng;
        }
        // 详细地址
        if(isset(request()->store_address)){
            $store_model->store_address = request()->store_address;
        }
        // 地区信息
        if(isset(request()->area_info)){
            $store_model->area_info = request()->area_info;
        }
        // 营业执照
        if(isset(request()->business_license)){
            $store_model->business_license = request()->business_license;
        }
        // 营业执照号码
        if(isset(request()->business_license_no)){
            $store_model->business_license_no = request()->business_license_no;
        }
        // 法人
        if(isset(request()->legal_person)){
            $store_model->legal_person = request()->legal_person;
        }
        // 法人电话
        if(isset(request()->store_phone)){
            $store_model->store_phone = request()->store_phone;
        }
        // 身份证号码
        if(isset(request()->id_card_no)){
            $store_model->id_card_no = request()->id_card_no;
        }
        // 身份证上
        if(isset(request()->id_card_t)){
            $store_model->id_card_t = request()->id_card_t;
        }
        // 身份证下
        if(isset(request()->id_card_b)){
            $store_model->id_card_b = request()->id_card_b;
        }
        // 紧急联系人
        if(isset(request()->emergency_contact)){
            $store_model->emergency_contact = request()->emergency_contact;
        }
        // 紧急联系人电话
        if(isset(request()->emergency_contact_phone)){
            $store_model->emergency_contact_phone = request()->emergency_contact_phone;
        }
        // 商家商品栏目
        if(isset(request()->store_classes)){
            $store_classes_model = new StoreClass();
            $store_classes_info = $store_classes_model->where('store_id',$store_id)->first();
            foreach(request()->store_classes as $k=>$v){
                $class_id[$k] = [];
                $class_name[$k] = [];
                foreach($v as $vo){
                    $class_id[$k][] = $vo['id'];
                    $class_name[$k][] = $vo['name'];
                }
            }
            
            $data = [
                'store_id'=>$store_id,
                'class_id'=>json_encode($class_id),
                'class_name'=>json_encode($class_name),
            ];
            if(empty($store_classes_info)){
                $store_classes_model->insert($data);
            }else{
                $store_classes_model->where('store_id',$store_id)->update($data);
            }
        }
        try{
            $store_model->save();
        }catch(\Exception $e){
            Log::channel('qwlog')->debug($e->getMessage());
            return $this->format_error(__('stores.store_edit_error'));
        }

        return $this->format([],__('base.success'));
        
    }


    // 编辑店铺状态
    public function editStoreStatus($store_id,$data = []){
        $store_model = new Store();
        $store_model = $store_model->find($store_id);

        // 店铺状态
        if(isset(request()->store_status)){
            $store_model->store_status = request()->store_status;
        }
        // 审核状态
        if(isset(request()->store_verify)){
            $store_model->store_verify = request()->store_verify;
        }
        // 审核失败原因
        if(isset(request()->store_refuse_info)){
            $store_model->store_refuse_info = request()->store_refuse_info;
        }

        // 判断是否存在则修改
        if(isset($data['store_verify'])){
            $store_model->store_verify = $data['store_verify'];
        }

        try{
            $store_model->save();
        }catch(\Exception $e){
            Log::channel('qwlog')->debug($e->getMessage());
            return $this->format_error(__('stores.store_edit_error'));
        }

        return $this->format([],__('base.success'));
        
    }
}
