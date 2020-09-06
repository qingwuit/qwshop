<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


/**
 * 
 * @author hg <364825702@qq.com>
 * 商城总后台 路由
 * 
 */
Route::any('/test','Admin\LoginController@test'); // 测试接口

Route::namespace('Admin')->prefix('Admin')->group(function(){

    Route::post('/login','LoginController@login'); // 登陆
    Route::get('/logout','LoginController@logout'); // 退出账号
    Route::get('/check_login','LoginController@check_login'); // 检测登陆

    Route::group(['middleware'=>'jwt.admin'],function(){
        
        Route::apiResources([
            'admins'=>'AdminController', // 超级管理员
            'roles'=>'RoleController', // 用户角色
            'menus'=>'MenuController', // 用户菜单
            'permissions'=>'PermissionController', // 角色权限
            'permission_groups'=>'PermissionGroupController', // 接口权限分组
        ]); 

        // 菜单处理
        Route::get('/menus/cache/clear','MenuController@clear_cache'); // 缓存清除接口

        // 配置中心
        Route::apiResource('configs','ConfigController')->except(['update','show','destroy']);
        Route::post('/configs/upload/logo','ConfigController@config_logo'); // 配置中心图上传(Logo)
        Route::post('/configs/upload/icon','ConfigController@config_icon'); // 配置中心上传(icon)

        Route::apiResource('agreements','AgreementController'); // 站点协议 

        // 编辑器上传图片接口
        Route::post('/editor/upload','EditorController@editor'); 

        // 短信管理
        Route::apiResource('sms_logs','SmsLogController')->except(['update','show','store']); // 短信日志
        Route::apiResource('sms_signs','SmsSignController'); // 短信签名

        // 商品分类
        Route::apiResource('goods_classes','GoodsClassController');
        Route::post('/goods_classes/upload/thumb','GoodsClassController@goods_class_upload'); // 缩略图上传
        Route::get('/goods_classes/cache/clear','GoodsClassController@clear_cache'); // 缓存清除商品分类

        // 店铺管理
        Route::apiResource('stores','StoreController')->except(['store']);

        // 全国省市区地址
        Route::apiResource('areas','AreaController');
        Route::get('/areas/cache/clear','AreaController@clear_cache'); // 缓存清除商品分类

        // 商品品牌
        Route::apiResource('goods_brands','GoodsBrandController');
        Route::post('/goods_brands/upload/thumb','GoodsBrandController@goods_brand_upload'); // 缩略图上传

        // 商品管理
        Route::apiResource('goods','GoodsController')->except(['store']);

        // 广告位管理
        Route::apiResource('adv_positions','AdvPositionController');
        Route::apiResource('advs','AdvController');
        Route::post('/advs/upload/thumb','AdvController@adv_upload'); // 缩略图上传
    });

    
});


/**
 * 
 * @author hg <364825702@qq.com>
 * 商城商家后台 路由
 * 
 */
Route::namespace('Seller')->prefix('Seller')->group(function(){

    Route::post('/login','LoginController@login'); // 登陆
    Route::get('/logout','LoginController@logout'); // 退出账号
    Route::get('/check_login','LoginController@check_login'); // 检测登陆

    Route::group(['middleware'=>'jwt.user'],function(){

        // 商家菜单
        Route::apiResource('menus','MenuController')->except(['update','show','store','destroy']);

        // 属性规格
        Route::apiResource('goods_attrs','GoodsAttrController');

        // 商品管理
        Route::apiResource('goods','GoodsController');
        Route::get('store_goods_classes','GoodsController@store_goods_classes'); // 获取店铺有权的商品栏目信息
        Route::post('/goods/upload/images','GoodsController@goods_upload'); // 图片上传

        // 商品品牌
        Route::apiResource('goods_brands','GoodsBrandController')->except(['update','show','store','destroy']);
    });

    
});

/**
 * 
 * @author hg <364825702@qq.com>
 * 商城PC端 路由
 * 
 */
Route::namespace('Home')->group(function(){

    Route::post('/login','LoginController@login'); // 登陆
    Route::get('/logout','LoginController@logout'); // 退出账号
    Route::get('/check_login','LoginController@check_login'); // 检测登陆

    // 获取站点协议
    Route::get('/agreement/{ename}','AgreementController@show'); 

    // 网站公共配置获取
    Route::get('/common','CommonController@common'); 

    // 获取商品栏目
    Route::get('/goods_classes','GoodsClassController@goods_classes'); 

    // PC端首页
    Route::get('/index','IndexController@index'); 

    // 商品
    Route::get('/goods/{id}','GoodsController@goods_info'); // 获取商品详情
    Route::post('/goods/search/all','GoodsController@goods_search'); // 搜索商品列表

     
    Route::group(['middleware'=>'jwt.user'],function(){

        // 购物车
        Route::apiResource('carts','CartController')->except(['show']);

        // 用户收货地址
        Route::apiResource('addresses','AddressController');
        Route::put('/addresses/default/set','AddressController@set_default'); // 设置默认地址
        Route::get('/addresses/default/get','AddressController@get_default'); // 获取默认地址

        // 订单处理
        Route::get('/order/create_order_before','OrderController@create_order_before'); // 生成订单前处理
        Route::get('/order/create_order_after','OrderController@create_order_after'); // 生成订单后处理
        Route::post('/order/create_order','OrderController@create_order'); // 生成订单

        // 商家入驻
        Route::get('/store/store_verify','StoreController@store_verify'); // 商家状态
        Route::match(['get','post'],'/store/store_join','StoreController@store_join');// 商家入驻
        Route::post('/store/join/upload','StoreController@store_join_upload'); // 商家入驻图片上传

        // 全国省市区地址获取
        Route::get('/areas','AreaController@areas'); // 商家状态

        
    });


    
});