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
            'users'=>'UserController', // 平台用户
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

        // 物流公司
        Route::apiResource('expresses','ExpressController');

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

        // 订单管理 
        Route::apiResource('orders','OrderController')->except(['store']);

        // 订单评论
        Route::apiResource('order_comments','OrderCommentController')->except(['store','update']);
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

        // 订单管理 
        Route::apiResource('orders','OrderController')->except(['store','destroy']);

        // 订单评论
        Route::apiResource('order_comments','OrderCommentController')->except(['store','destroy']);

        // 物流公司
        Route::apiResource('expresses','ExpressController')->except(['update','store','destroy']);
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
    Route::post('/register','LoginController@register'); // 注册
    Route::post('/forget_password','LoginController@forget_password'); // 忘记密码
    Route::get('/logout','LoginController@logout'); // 退出账号
    Route::get('/check_login','LoginController@check_login'); // 检测登陆
    Route::get('/send_sms','LoginController@send_sms'); // 发送短信

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
        Route::get('/carts/cart_count','CartController@cart_count'); // 获取购物车商品数量


        // 用户收货地址
        Route::apiResource('addresses','AddressController');
        Route::put('/addresses/default/set','AddressController@set_default'); // 设置默认地址
        Route::get('/addresses/default/get','AddressController@get_default'); // 获取默认地址

        // 个人中心首页
        Route::get('/users/default','UserController@default'); // 默认页面

        // 用户资料
        Route::get('/users/info','UserController@user_info'); // 获取用户资料
        Route::match(['get','put'],'/users/edit_user','UserController@edit_user'); // 修改用户资料
        Route::post('/users/avatar_upload','UserController@avatar_upload'); // 用户头像上传

        // 用户认证
        Route::get('/users/user_check','UserCheckController@user_check'); // 获取用户认证资料
        Route::post('/users/edit_user_check','UserCheckController@edit_user_check'); // 修改用户认证资料
        Route::post('/users/user_check_upload','UserCheckController@user_check_upload'); // 用户认证图片上传

        // 收藏/关注
        Route::apiResource('favorites','FavoriteController')->except(['update']);

        // 资金日志
        Route::apiResource('money_logs','MoneyLogController')->except(['update','show','store','destroy']);

        // 订单列表
        Route::get('/order','OrderController@get_orders'); // 获取订单列表

        // 订单处理
        Route::get('/order/create_order_before','OrderController@create_order_before'); // 生成订单前处理
        Route::get('/order/create_order_after','OrderController@create_order_after'); // 生成订单后处理
        Route::post('/order/create_order','OrderController@create_order'); // 生成订单
        Route::post('/order/pay','OrderController@pay'); // 订单支付
        Route::put('/order/edit_order_status','OrderController@edit_order_status'); // 取消订单
        Route::get('/order/get_order_info/{id}','OrderController@get_order_info'); // 查看订单信息

        // 评论管理
        Route::apiResource('order_comments','OrderCommentController')->except(['destroy']);
        Route::post('/order_comments/thumb/upload','OrderCommentController@comment_upload'); // 评论管理图片上传

        // 商家入驻
        Route::get('/store/store_verify','StoreController@store_verify'); // 商家状态
        Route::match(['get','post'],'/store/store_join','StoreController@store_join');// 商家入驻
        Route::post('/store/join/upload','StoreController@store_join_upload'); // 商家入驻图片上传

        // 全国省市区地址获取
        Route::get('/areas','AreaController@areas'); // 商家状态

        
    });


    
});


/**
 * 
 * @author hg <364825702@qq.com>
 * 商城支付回调|其他回调 路由
 * 
 */
Route::namespace('PayCallBack')->group(function(){

    Route::any('/payment/{name}','PaymentController@payment'); // 回调地址  [/api/payment/wechat] | [/api/payment/ali]
    
});