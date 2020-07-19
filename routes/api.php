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

        // 属性规格
        Route::apiResource('goods_attrs','GoodsAttrController');

        // 商品管理
        Route::apiResource('goods','GoodsController');
        Route::post('/goods/upload/images','GoodsController@goods_upload'); // 图片上传
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

     
    Route::group(['middleware'=>'jwt.user'],function(){

        // 商家入驻商家状态
        Route::get('/store/store_verify','StoreController@store_verify');
    });


    
});