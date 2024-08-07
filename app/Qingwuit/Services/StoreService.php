<?php

namespace App\Qingwuit\Services;

use App\Http\Resources\StoreHomeCollection;
use App\Http\Resources\StoreResource;
use App\Qingwuit\Models\Store;
use App\Qingwuit\Models\StoreClass;
use Illuminate\Support\Facades\DB;

class StoreService extends BaseService
{
    public $storeStatus = ['store_status' => 1, 'store_verify' => 4]; // 正常店铺

    // 入驻
    public function createStore($auth = 'users')
    {
        try {
            $userInfo = $this->getUser($auth)['data'];
        } catch (\Exception $e) {
            return $this->formatError($e->getMessage());
        }

        $userId = $userInfo['id'];

        if (!empty($userId['belong_id'])) {
            return $this->formatError(__('tip.store.subLimit'));
        }

        $store = $this->getService('Store', true)->where('user_id', $userId)->first();

        if ($store) {
            return $this->formatError(__('tip.store.defined'), $store);
        }

        $rs = $this->getService('Store', true)->create([
            'user_id'           =>  $userId,
            'store_verify'      =>  1,
            'store_status'      =>  0,
            'store_refuse_info' =>  '',
            'after_sale_service' =>  '',
        ]);

        return $this->format($rs, __('tip.success'));
    }

    // 编辑店铺数据
    public function editStore($storeId = null, $whereName = 'user_id', $auth = 'users')
    {
        if (empty($storeId)) {
            return $this->formatError(__('tip.store.notMall'));
        }

        try {
            $userInfo = $this->getUser($auth)['data'];
        } catch (\Exception $e) {
            return $this->formatError($e->getMessage());
        }

        $userId = $whereName == 'belong_id' ? $userInfo['belong_id'] : $userInfo['id'];
        if ($whereName == 'user_id') {
            $storeId = $userId;
        }
        if ($whereName == 'belong_id') {
            $whereName = 'user_id';
        }

        $store_model = new Store();
        $store_model = $store_model->where($whereName, $storeId)->first();
        if ($whereName != 'user_id' && $auth != 'admins') {
            if ($store_model->user_id != $userId && $store_model->user_id != $userInfo['belong_id']) {
                return $this->formatError(__('tip.store.notMall'));
            }
        }

        // 店铺名
        if (isset(request()->store_name)) {
            $store_model->store_name = request()->store_name;
        }
        // 店铺LOGO
        if (isset(request()->store_logo)) {
            $store_model->store_logo = request()->store_logo;
        }
        // 店铺门面
        if (isset(request()->store_face_image)) {
            $store_model->store_face_image = request()->store_face_image;
        }
        // 店铺幻灯片
        if (isset(request()->store_slide)) {
            $store_model->store_slide = request()->store_slide;
        }
        // 店铺手机幻灯片
        if (isset(request()->store_mobile_slide)) {
            $store_model->store_mobile_slide = request()->store_mobile_slide;
        }
        // 店铺电话
        if (isset(request()->store_mobile)) {
            $store_model->store_mobile = request()->store_mobile;
        }
        // 店铺描述
        if (isset(request()->store_description)) {
            $store_model->store_description = request()->store_description;
        }
        // 公司名称
        if (isset(request()->store_company_name)) {
            $store_model->store_company_name = request()->store_company_name;
        }
        // 省ID
        if (isset(request()->province_id)) {
            $store_model->province_id = request()->province_id;
        }
        // 市ID
        if (isset(request()->city_id)) {
            $store_model->city_id = request()->city_id;
        }
        // 区ID
        if (isset(request()->region_id)) {
            $store_model->region_id = request()->region_id;
        }
        if (isset(request()->area)) {
            $areaList = $this->getService('Area', true)->whereIn('id', request()->area)->get();
            if (!$areaList->isEmpty()) {
                $areaInfo = '';
                foreach ($areaList as $k => $v) {
                    $areaInfo .= ' ' . $v['name'];
                    if ($k == 0) $store_model->province_id = $v['id'];
                    if ($k == 1) $store_model->city_id = $v['id'];
                    if ($k == 2) $store_model->region_id = $v['id'];
                }
                $store_model->area_info =  ltrim($areaInfo, ' ');
            }
        }
        // 纬度
        if (isset(request()->store_lat)) {
            $store_model->store_lat = request()->store_lat;
        }
        // 经度
        if (isset(request()->store_lng)) {
            $store_model->store_lng = request()->store_lng;
        }
        // 详细地址
        if (isset(request()->store_address)) {
            $store_model->store_address = request()->store_address;
        }
        // 地区信息
        // if(isset(request()->area_info)){
        //     $store_model->area_info = request()->area_info;
        // }
        // 营业执照
        if (isset(request()->business_license)) {
            $store_model->business_license = request()->business_license;
        }
        // 营业执照号码
        if (isset(request()->business_license_no)) {
            $store_model->business_license_no = request()->business_license_no;
        }
        // 法人
        if (isset(request()->legal_person)) {
            $store_model->legal_person = request()->legal_person;
        }
        // 法人电话
        if (isset(request()->store_phone)) {
            $store_model->store_phone = request()->store_phone;
        }
        // 身份证号码
        if (isset(request()->id_card_no)) {
            $store_model->id_card_no = request()->id_card_no;
        }
        // 身份证上
        if (isset(request()->id_card_t)) {
            $store_model->id_card_t = request()->id_card_t;
        }
        // 身份证下
        if (isset(request()->id_card_b)) {
            $store_model->id_card_b = request()->id_card_b;
        }
        // 紧急联系人
        if (isset(request()->emergency_contact)) {
            $store_model->emergency_contact = request()->emergency_contact;
        }
        // 紧急联系人电话
        if (isset(request()->emergency_contact_phone)) {
            $store_model->emergency_contact_phone = request()->emergency_contact_phone;
        }
        // 售后服务
        if (isset(request()->after_sale_service)) {
            $store_model->after_sale_service = request()->after_sale_service;
        }
        // 商家商品栏目
        if (isset(request()->class_id) && !empty(request()->class_id)) {
            $store_classes_model = new StoreClass();
            $store_classes_info = $store_classes_model->where('store_id', $store_model->id)->first();
            $class_id = [];
            $rcl = request('class_id');
            if (!is_array(request()->class_id)) $rcl = json_decode($rcl);
            foreach ($rcl as $k => $v) {
                if (count($v) > 3) {
                    $class_id[] = $v;
                }
            }

            $data = [
                'store_id' => $store_model->id,
                'class_id' => is_array(request('class_id', [])) ? json_encode(request('class_id', [])) : request('class_id'),
                'class_name' => '',
            ];
            if (empty($store_classes_info)) {
                $store_classes_model->insert($data);
            } else {
                $store_classes_model->where('store_id', $store_model->id)->update($data);
            }
        }

        if ($auth != 'admins') {
            $store_verify = $store_model->store_verify;
            if ($store_model->store_verify == 1) {
                $store_verify = 2;
            } //待审核
            if ($store_model->store_verify == 3) {
                $store_verify = 2;
            } //审核失败重修改
            $store_model->store_verify = $store_verify;
        } else {
            // 状态
            if (isset(request()->store_status)) {
                $store_model->store_status = request()->store_status;
            }
            // 审核状态
            if (isset(request()->store_verify)) {
                $store_model->store_verify = request()->store_verify;
            }
        }

        try {
            $store_model->save();
        } catch (\Exception $e) {
            return $this->formatError(__('tip.error'));
        }

        return $this->format([], __('tip.success'));
    }

    // 编辑店铺状态数据
    public function editStoreStatus($storeId = null, $data = [])
    {
        if (empty($storeId)) {
            return $this->formatError(__('tip.store.notMall'));
        }

        $store_model = new Store();
        $store_model = $store_model->find($storeId);

        // 店铺状态
        if (isset(request()->store_status)) {
            $store_model->store_status = request()->store_status;
        }
        // 审核状态
        if (isset(request()->store_verify)) {
            $store_model->store_verify = request()->store_verify;
        }
        // 审核失败原因
        if (isset(request()->store_refuse_info)) {
            $store_model->store_refuse_info = request()->store_refuse_info;
        }

        // 判断是否存在则修改
        if (isset($data['store_verify'])) {
            $store_model->store_verify = $data['store_verify'];
        }

        try {
            $store_model->save();
        } catch (\Exception $e) {
            return $this->formatError(__('tip.error'));
        }

        return $this->format([], __('tip.success'));
    }

    // 获取店铺信息
    public function getStoreInfo($storeId = null, $whereName = 'user_id', $auth = 'users')
    {
        if (empty($storeId)) {
            return $this->formatError(__('tip.store.notMall'));
        }

        try {
            $userInfo = $this->getUser($auth)['data'];
        } catch (\Exception $e) {
            return $this->formatError($e->getMessage());
        }

        // $userId = $whereName=='belong_id'?$userInfo['belong_id']:$userInfo['id'];
        $userId = empty($userInfo['belong_id']) ? $userInfo['id'] : $userInfo['belong_id'];
        if ($whereName == 'user_id') {
            $storeId = $userId;
        }
        // if($whereName == 'belong_id') $whereName = 'user_id';

        $store_model = new Store();
        $store_model = $store_model->where($whereName, $storeId)->first();
        if ($whereName != 'user_id') {
            if ($store_model->user_id != $userId && $store_model->user_id != $userInfo['belong_id']) {
                return $this->formatError(__('tip.store.notMall'));
            }
        }

        if (!$store_model) throw new \Exception('store not join.');
        return $this->format(new StoreResource($store_model));
    }

    // 获取店铺ID
    public function getStoreId($whereName = 'user_id', $auth = 'users')
    {
        try {
            $userInfo = $this->getUser($auth)['data'];
        } catch (\Exception $e) {
            return $this->formatError($e->getMessage());
        }
        $userId = empty($userInfo['belong_id']) ? $userInfo['id'] : $userInfo['belong_id'];
        $store_model = new Store();
        $store_model = $store_model->select('id')->where($whereName, $userId)->first();
        if (!$store_model) {
            return $this->formatError(__('tip.store.notMall') . '-2');
        }
        return $this->format($store_model->id);
    }

    // 获取店铺信息和评分信息
    public function getStoreInfoAndRate($storeId)
    {
        $storeInfo = $this->getService('Store', true)->find($storeId)->toArray();
        $info = $this->getService('OrderComment', true)->where('store_id', $storeId)->first([
            DB::raw('avg(score) as scoreAll'),
            DB::raw('avg(agree) as agreeAll'),
            DB::raw('avg(service) as serviceAll'),
            DB::raw('avg(speed) as speedAll'),
        ])->toArray();
        foreach ($info as $k => $v) {
            $info[$k] = intval($v) == 0 ? 5 : intval($v);
        }
        $storeInfo['rate'] = $info;
        unset($storeInfo['id_card_b'], $storeInfo['id_card_t'], $storeInfo['id_card_no'], $storeInfo['store_money'], $storeInfo['legal_person'], $storeInfo['emergency_contact_phone'], $storeInfo['emergency_contact'], $storeInfo['business_license']);
        return $this->format($storeInfo);
    }

    // 无权限获取店铺
    public function stores()
    {
        $params = request()->params ?? '';
        $lat = request()->lat ?? '39.56';
        $lng = request()->lng ?? '116.20'; // 默认北京的经纬度

        $distance = "ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN(('$lat' * PI() / 180 - store_lat * PI() / 180) / 2),2) + COS($lat * PI() / 180) * COS(store_lat * PI() / 180) * POW(SIN(('$lng' * PI() / 180 - store_lng * PI() / 180) / 2),2))) * 1000 )  AS distance ";

        $storeModel = $this->getService('Store', true)->select(DB::raw('*,' . $distance))->withCount(['comments', 'comments as good_comment' => function ($q) {
            $q->whereRaw('(score+agree+speed+service)>=15');
        }])->where($this->storeStatus);

        try {
            if (!empty($params)) {
                $params_array = json_decode(base64_decode($params), true);
                // 排序
                if (isset($params_array['sort_type']) && !empty($params_array['sort_type'])) {
                    $storeModel = $storeModel->orderBy($params_array['sort_type'], $params_array['sort_order']);
                } else {
                    $storeModel = $storeModel->orderBy('distance', $params_array['sort_order'] ?? 'desc')->orderBy('id', $params_array['sort_order'] ?? 'desc');
                }

                // 关键词
                if (isset($params_array['keywords']) && !empty($params_array['keywords'])) {
                    $params_array['keywords'] = urldecode($params_array['keywords']);
                    $storeModel = $storeModel->where('store_name', 'like', '%' . $params_array['keywords'] . '%');
                }
            }
            $list = $storeModel->paginate(request()->per_page ?? 30);
            return $this->format(new StoreHomeCollection($list));
        } catch (\Exception $e) {
            return $this->formatError($e->getMessage());
        }
    }
}
