<?php

namespace App\Qingwuit\Services;

use App\Http\Resources\GoodsHomeCollection;
use App\Http\Resources\GoodsHomeSearchCollection;
use Illuminate\Support\Facades\DB;

class GoodsService extends BaseService
{
    protected $status = ['goods_status' => 1, 'goods_verify' => 1];
    public function addGoods($auth = 'users')
    {
        $goodsModel = $this->getService('Goods', true);
        $storeId = 0;
        if ($auth == 'users') {
            $storeId = $this->getService('Store')->getStoreId()['data'];
        }
        $data = [
            'store_id'              => $storeId ?? 0,
            'goods_name'            => request()->goods_name,                         // 商品名
            'goods_subname'         => request()->goods_subname ?? '',                  // 副标题
            'goods_no'              => request()->goods_no ?? '',                       // 商品编号
            'brand_id'              => request()->brand_id ?? 0,                           // 商品品牌
            'class_id'              => request()->class_id ?? 0,                        // 商品分类
            'goods_master_image'    => request()->goods_master_image,                 // 商品主图
            'goods_price'           => abs(request()->goods_price ?? 0),                // 商品价格
            'goods_market_price'    => abs(request()->goods_market_price ?? 0),         // 商品市场价
            'goods_weight'          => abs(request()->goods_weight ?? 0),               // 商品重量
            'goods_stock'           => abs(request()->goods_stock ?? 0),                // 商品库存
            'goods_content'         => request()->goods_content ?? '',                  // 商品内容详情
            'goods_content_mobile'  => request()->goods_content_mobile ?? '',           // 商品内容详情（手机）
            'goods_status'          => abs(request()->goods_status) ?? 0,               // 商品上架状态
            'freight_id'            => intval(request()->freight_id) ?? 0,              // 运费模版ID
            'goods_images'          => implode(',', request()->goods_images ?? []),
        ];

        // 判断是否开启添加商品审核
        $storeConfig = $this->getService('Configs')->getFormatConfig('store');
        if (!empty($storeConfig)) {
            if ($storeConfig['goods_verify']) {
                $data['goods_verify'] = 2;
            }
        }

        try {
            DB::beginTransaction();
            $goodsModel = $goodsModel->create($data);
            // 规格处理
            if (isset(request()->skuList) && !empty(request()->skuList)) {
                $skuData = [];
                foreach (request()->skuList as $k => $v) {
                    $skuData[$k]['goods_image'] = $v['goods_image'] ?? ''; // sku图片
                    $skuData[$k]['spec_id'] = implode(',', $v['spec_id']); // sku 属性
                    $skuData[$k]['sku_name'] = implode(',', $v['sku_name']); // sku名称
                    $skuData[$k]['goods_price'] = abs($v['goods_price'] ?? 0); // sku价格
                    $skuData[$k]['goods_market_price'] = abs($v['goods_market_price'] ?? 0); // sku市场价
                    $skuData[$k]['goods_stock'] = abs($v['goods_stock'] ?? 0); // sku库存
                    $skuData[$k]['goods_weight'] = abs($v['goods_weight'] ?? 0); // sku 重量
                }
                $goodsModel->goods_skus()->createMany($skuData);
            }
            DB::commit();
            return $this->format([], __('tip.success'));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->formatError(__('tip.error'), $e->getMessage());
        }
    }


    // 修改商品
    public function editGoods($goodsId, $auth = 'users')
    {
        $storeId = 0;
        if ($auth == 'users') {
            $storeId = $this->getService('Store')->getStoreId()['data'];
        }
        $goodsModel = $this->getService('Goods', true)->where('id', $goodsId);
        if ($auth == 'users') $goodsModel = $goodsModel->where('store_id', $storeId);
        $goodsModel = $goodsModel->first();

        if (!$goodsModel) {
            return $this->formatError(__('tip.error'));
        }

        // 商品名
        if (isset(request()->goods_name) && !empty(request()->goods_name)) {
            $goodsModel->goods_name = request()->goods_name;
        }
        // 副标题
        if (isset(request()->goods_subname) && !empty(request()->goods_subname)) {
            $goodsModel->goods_subname = request()->goods_subname;
        }
        // 商品编号
        if (isset(request()->goods_no) && !empty(request()->goods_no)) {
            $goodsModel->goods_no = request()->goods_no;
        }
        // 商品品牌
        if (isset(request()->brand_id) && !empty(request()->brand_id)) {
            $goodsModel->brand_id = request()->brand_id;
        }
        // 商品分类
        if (isset(request()->class_id) && !empty(request()->class_id)) {
            $goodsModel->class_id = request()->class_id;
        }
        // 商品主图
        if (isset(request()->goods_master_image) && !empty(request()->goods_master_image)) {
            $goodsModel->goods_master_image = request()->goods_master_image;
        }
        // 商品价格
        if (isset(request()->goods_price) && !empty(request()->goods_price)) {
            $goodsModel->goods_price = abs(request()->goods_price ?? 0);
        }
        // 商品市场价
        if (isset(request()->goods_market_price) && !empty(request()->goods_market_price)) {
            $goodsModel->goods_market_price = abs(request()->goods_market_price ?? 0);
        }
        // 商品重量
        if (isset(request()->goods_weight) && !empty(request()->goods_weight)) {
            $goodsModel->goods_weight = abs(request()->goods_weight ?? 0);
        }
        // 商品库存
        if (isset(request()->goods_stock) && !empty(request()->goods_stock)) {
            $goodsModel->goods_stock = abs(request()->goods_stock ?? 0);
        }
        // 商品内容详情
        if (isset(request()->goods_content) && !empty(request()->goods_content)) {
            $goodsModel->goods_content = request()->goods_content;
        }
        // 商品内容详情（手机）
        if (isset(request()->goods_content_mobile) && !empty(request()->goods_content_mobile)) {
            $goodsModel->goods_content_mobile = request()->goods_content_mobile;
        }
        // 商品上架状态
        if (isset(request()->goods_status)) {
            $goodsModel->goods_status = abs(request()->goods_status ?? 0);
        }
        // 商品推荐状态
        if (isset(request()->is_master)) {
            $goodsModel->is_master = abs(request()->is_master ?? 0);
        }
        // 商品快递情况状态
        if (isset(request()->freight_id)) {
            $goodsModel->freight_id = intval(request()->freight_id ?? 0);
        }
        // 商品图片
        if (isset(request()->goods_images) && !empty(request()->goods_images)) {
            $goodsModel->goods_images = implode(',', request()->goods_images ?? []);
        }

        // 判断是否开启添加商品审核
        $configs = $this->getService('Configs')->getFormatConfig('store');
        if (!empty($configs) && isset($configs['goods_verify'])) {
            // 如果是内容标题修改则进行审核（暂时不写）
            if ($configs['goods_verify'] && $auth == 'users') {
                if (
                    (!empty(request('goods_name')) && request('goods_name') != $goodsModel->goods_name) ||
                    (!empty(request('goods_subname')) && request('goods_subname') != $goodsModel->goods_subname) ||
                    (!empty(request('goods_content')) && request('goods_content') != $goodsModel->goods_content)
                ) {
                    $goodsModel->goods_verify = 2;
                }
            } else {
                $goodsModel->goods_verify = 1;
            }

            if ($auth != 'users') $goodsModel->goods_verify = request('goods_verify', 1);
        }

        try {
            DB::beginTransaction();
            $goodsModel = $goodsModel->save();
            // 规格处理
            if (isset(request()->skuList) && !empty(request()->skuList)) {
                $skuData = [];
                $skuId = []; // 修改的skuID 不存在则准备删除
                foreach (request()->skuList as $k => $v) {
                    if (isset($v['id']) && !empty($v['id'])) {
                        // 如果ID不为空则代表存在此sku 进行修改
                        $skuId[] = $v['id'];
                        $this->getService('GoodsSku', true)->where('goods_id', $goodsId)->where('id', $v['id'])->update([
                            'goods_image'           => $v['goods_image'] ?? '', // sku图片
                            'spec_id'               => implode(',', $v['spec_id']), // sku 属性
                            'sku_name'              => implode(',', $v['sku_name']), // sku名称
                            'goods_price'           => abs($v['goods_price'] ?? 0), // sku价格
                            'goods_market_price'    => abs($v['goods_market_price'] ?? 0), // sku市场价
                            'goods_stock'           => abs($v['goods_stock'] ?? 0), // sku库存
                            'goods_weight'          => abs($v['goods_weight'] ?? 0), // sku 重量
                        ]);
                    } else {
                        // 否则进行插入数据库
                        $skuData[$k]['goods_image'] = $v['goods_image'] ?? ''; // sku图片
                        $skuData[$k]['spec_id'] = implode(',', $v['spec_id']); // sku 属性
                        $skuData[$k]['sku_name'] = implode(',', $v['sku_name']); // sku名称
                        $skuData[$k]['goods_price'] = abs($v['goods_price'] ?? 0); // sku价格
                        $skuData[$k]['goods_market_price'] = abs($v['goods_market_price'] ?? 0); // sku市场价
                        $skuData[$k]['goods_stock'] = abs($v['goods_stock'] ?? 0); // sku库存
                        $skuData[$k]['goods_weight'] = abs($v['goods_weight'] ?? 0); // sku 重量
                    }
                }

                // 如果ID不为空则代表存在此sku 进行修改
                $skuModel = $this->getService('GoodsSku', true)->where('goods_id', $goodsId);
                if(!empty($skuId)) $skuModel->whereNotIn('id', $skuId)->delete();
                if(empty($skuId)) $skuModel->delete();                

                // 新建不存在sku进行插入数据库
                if (!empty($skuData)) {
                    $goodsModel = $this->getService('Goods', true)->find($goodsId);
                    $goodsModel->goods_skus()->createMany($skuData);
                }
            } else {
                // 清空所有sku
                $this->getService('GoodsSku', true)->where('goods_id', $goodsId)->delete();
            }
            DB::commit();
            return $this->format([], __('tip.success'));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->formaterror(__('tip.error'), $e->getMessage());
        }
    }

    // 修改商品的状态审核
    public function editGoodsVerify($goodsId, $status = 1, $msg = '')
    {
        $goodsModel = $this->getService('Goods', true)->where('id', $goodsId);
        $data = [
            'goods_verify'      =>  $status,
        ];
        if ($status == 2) {
            $data['refuse_info'] = $msg;
        }
        $rs = $goodsModel->update($data);
        return $this->format($rs);
    }

    // 获取商品详情
    public function getGoodsInfo($goodsId)
    {
        $goodsInfo = $this->getService('Goods', true)->find($goodsId);
        if (!$goodsInfo) {
            return $this->formatError(__('tip.error'));
        }

        // 获取商品分类信息
        if ($goodsInfo->class_id == 0) {
            return $this->formatError(__('tip.error') . 'c');
        }
        $classData = [];
        $classData[] = $this->getService('GoodsClass', true)->find($goodsInfo->class_id);
        $classData[] = $this->getService('GoodsClass', true)->find($classData[0]->pid);
        $classData[] = $this->getService('GoodsClass', true)->find($classData[1]->pid);
        $goodsInfo['classList'] = $classData;

        // 图片转化
        if (!empty($goodsInfo->goods_images)) {
            $goodsInfo['goods_images'] = explode(',', $goodsInfo->goods_images);
            $goodsInfo['goods_images_thumb_150'] = $this->getService('Tool')->thumb_array($goodsInfo['goods_images'], 150);
            $goodsInfo['goods_images_thumb_400'] = $this->getService('Tool')->thumb_array($goodsInfo['goods_images'], 400);
        }

        // 获取处理后的规格信息
        $sku = $this->getService('GoodsSku', true)->where('goods_id', $goodsId)->get()->toArray();
        if (!empty($sku)) {
            $skuList = [];
            $spec_id = [];
            foreach ($sku as $v) {
                $v['spec_id'] = explode(',', $v['spec_id']);
                $v['sku_name'] = explode(',', $v['sku_name']);
                $spec_id = array_merge($spec_id, $v['spec_id']);
                $skuList[] = $v;
            }
            $spec_id = array_unique($spec_id);
            $goods_spec = $this->getService('GoodsSpecs', true)->whereIn('id', $spec_id)->orderBy('id', 'desc')->get()->toArray();
            $attr_id = [];
            foreach ($goods_spec as $v) {
                if (!in_array($v['attr_id'], $attr_id)) $attr_id[] = $v['attr_id'];
            }
            $goods_attr = $this->getService('GoodsAttr', true)->whereIn('id', $attr_id)->with('specs')->orderBy('id', 'desc')->get()->toArray();
            foreach ($goods_attr as $k => $v) {
                foreach ($v['specs'] as $key => $vo) {
                    if (in_array($vo['id'], $spec_id)) {
                        $goods_attr[$k]['specs'][$key]['check'] = true;
                    } else {
                        if (request()->saveCheck) {
                            unset($goods_attr[$k]['specs'][$key]);
                        }
                    }
                }
                if (isset($goods_attr[$k]['specs']) && is_array($goods_attr[$k]['specs'])) $goods_attr[$k]['specs'] = array_merge($goods_attr[$k]['specs'], []);
            }
            $goodsInfo['attrList'] = $goods_attr;
            $goodsInfo['skuList'] = $skuList;
        }
        return $this->format($goodsInfo);
    }

    // 搜索 和 商品列表
    public function all()
    {
        $goodsModel = $this->getService('Goods', true);
        $params = request('params');
        try {
            if (!empty($params)) {
                $params_array = json_decode(base64_decode($params), true);
                // 品牌
                if (isset($params_array['brand_id']) && !empty($params_array['brand_id'])) {
                    $goodsModel = $goodsModel->where('brand_id', $params_array['brand_id']);
                }

                // 栏目
                if (isset($params_array['class_id']) && !empty($params_array['class_id'])) {
                    if (isset($params_array['deep'])) {
                        $classIds = [$params_array['class_id']];
                        if ($params_array['deep'] == 1) {
                            $classList = $this->getService('GoodsClass', true)->select('id')->where('pid', $params_array['class_id'])->get();
                            $classChildIds = [];
                            if (!$classList->isEmpty()) {
                                foreach ($classList as $cItem) {
                                    $classIds[] = $cItem->id;
                                    $classChildIds[] = $cItem->id;
                                }
                                $classList = $this->getService('GoodsClass', true)->select('id')->whereIn('pid', $classChildIds)->get();
                                if (!$classList->isEmpty()) {
                                    foreach ($classList as $cItem) {
                                        $classIds[] = $cItem->id;
                                    }
                                }
                            }
                        }
                        if ($params_array['deep'] == 2) {
                            $classList = $this->getService('GoodsClass', true)->select('id')->where('pid', $params_array['class_id'])->get();
                            $classChildIds = [];
                            if (!$classList->isEmpty()) {
                                foreach ($classList as $cItem) {
                                    $classIds[] = $cItem->id;
                                }
                            }
                        }
                        $params_array['class_id'] = $classIds;
                    }
                    if (is_array($params_array['class_id'])) {
                        $goodsModel = $goodsModel->whereIn('class_id', $params_array['class_id']);
                    } else {
                        $goodsModel = $goodsModel->where('class_id', $params_array['class_id']);
                    }
                }

                // 商家
                if (isset($params_array['store_id']) && !empty($params_array['store_id'])) {
                    $goodsModel = $goodsModel->where('store_id', $params_array['store_id']);
                }

                // 关键词
                if (isset($params_array['keywords']) && !empty($params_array['keywords'])) {
                    $params_array['keywords'] = urldecode($params_array['keywords']);
                    $goodsModel = $goodsModel->where('goods_name', 'like', '%' . $params_array['keywords'] . '%');
                }

                // 排序
                if (isset($params_array['sort_type']) && !empty($params_array['sort_type'])) {
                    $goodsModel = $goodsModel->orderBy($params_array['sort_type'], $params_array['sort_order']);
                } else {
                    $goodsModel = $goodsModel->orderBy('id', $params_array['sort_order'] ?? 'desc')->orderBy('goods_sale', $params_array['sort_order'] ?? 'desc');
                }
            }

            // 是否商家推荐
            if (!empty(request('is_recommend'))) {
                $goodsModel = $goodsModel->where('is_recommend', request('is_recommend'));
            }

            // 是否主站推荐
            if (!empty(request('is_master'))) {
                $goodsModel = $goodsModel->where('is_master', request('is_master'));
            }

            // 是否是拼团产品
            if (!empty(request('is_collective'))) {
                $goodsModel = $goodsModel->whereHas('collective');
            }

            // 是否是分销产品
            if (!empty(request('is_distribution'))) {
                $goodsModel = $goodsModel->whereHas('distribution');
            }

            $list = $goodsModel->where($this->status)
                ->with(['goods_skus' => function ($q) {
                    return $q->select('goods_id', 'goods_price', 'goods_stock', 'deleted_at')->orderBy('goods_price', 'asc');
                }, 'store', 'collective'])
                ->withCount('order_comment')
                ->whereHas('store', function ($q) {
                    return $q->where($this->getService('Store')->storeStatus);
                })
                ->paginate(intval(request('per_page')));
        } catch (\Exception $e) {
            return $this->formatError($e->getMessage());
        }

        return $this->format(new GoodsHomeSearchCollection($list));
    }

    public function master($goodsNum = 6)
    {
        $goodsModel = $this->getService('Goods', true);
        $goodsClass = $this->getService('Tool')->getChildren($this->getService('GoodsClass', true)->orderBy('is_sort', 'asc')->get()->toArray());
        $class = [];
        foreach ($goodsClass as $k => $v) {
            $class[$k]['name'] = $v['name'];
            $class[$k]['id'] = $v['id'];
            $class[$k]['goods'] = [];
            $class[$k]['class_id'] = [];
            foreach ($v['children'] as $vo) {
                if (isset($vo['children'])) {
                    foreach ($vo['children'] as $item) {
                        $class[$k]['class_id'][] = $item['id'];
                    }
                }
            }
        }

        foreach ($class as $k => $v) {
            $class[$k]['goods'] = new GoodsHomeCollection($goodsModel->whereHas('store', function ($q) {
                return $q->where($this->getService('Store')->storeStatus);
            })->with(['goods_skus' => function ($q) {
                return $q->orderBy('goods_price', 'asc');
            }])->where($this->status)->whereIn('class_id', $v['class_id'])->orderBy('is_master', 'desc')->take($goodsNum)->get());
            unset($class[$k]['class_id']);
        }

        return $this->format($class);
    }

    // 获取排序数量的商品
    public function sortGoods($id, $whereName = 'store_id', $take = 6, $where = [])
    {
        $model = $this->getService('Goods', true);
        if (!empty($where)) {
            $model = $model->where($where);
        }
        $list = $model->whereHas('store', function ($q) {
            return $q->where($this->getService('Store')->storeStatus);
        })->with(['goods_skus' => function ($q) {
            return $q->orderBy('goods_price', 'asc');
        }])->where($whereName, $id)->where($this->status)->take($take)->orderBy('goods_sale', 'desc')->get();
        return $this->format(new GoodsHomeCollection($list));
    }

    // 获取首页秒杀商品
    public function getHomeSeckillGoods()
    {
        try {
            $list = $this->getService('Goods', true)->where($this->status)
                ->whereHas('store', function ($q) {
                    return $q->where($this->getService('Store')->storeStatus);
                })
                ->with(['goods_skus' => function ($q) {
                    return $q->orderBy('goods_price', 'asc');
                }])
                // ->with(['goods_sku'=>function($q){
                //     return $q->select('goods_id','goods_price','goods_stock','goods_market_price')->orderBy('goods_price','asc');
                // }])
                ->whereHas('seckill', function ($q) {
                    if (!isset(request()->start_time) || request('start_time') == null) {
                        return $q->where('start_time', '<', now()->format('Y-m-d H') . ':00:00')->where('start_time', '>', now()->addHours(1)->format('Y-m-d H') . ':00:00');
                    }
                    return $q->where('start_time', '>=', now()->addHours(request()->start_time)->format('Y-m-d H') . ':00:00')->where('start_time', '<', now()->addHours(request()->start_time + 1)->format('Y-m-d H') . ':00:00');
                })
                ->orderBy('goods_sale', 'desc')
                ->paginate(request()->per_page ?? 30);
        } catch (\Exception $e) {
            return $this->formatError($e->getMessage());
        }

        return $this->format(new GoodsHomeSearchCollection($list));
    }
}
