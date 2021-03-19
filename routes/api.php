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
        Route::get('/menus/cache/clear','MenuController@clear_cache')->name('menus.clear_cache'); // 缓存清除接口

        // 配置中心
        Route::apiResource('configs','ConfigController')->except(['update','show','destroy']);
        Route::post('/configs/upload/logo','ConfigController@config_logo')->name('configs.config_logo'); // 配置中心图上传(Logo)
        Route::post('/configs/upload/icon','ConfigController@config_icon')->name('configs.config_icon'); // 配置中心上传(icon)

        Route::apiResource('agreements','AgreementController'); // 站点协议 
        Route::apiResource('articles','ArticleController'); // 文章，帮助中心 

        // 物流公司
        Route::apiResource('expresses','ExpressController');

        // 编辑器上传图片接口
        Route::post('/editor/upload','EditorController@editor')->name('public.editor');  // 富文本编辑器图上传

        // 短信管理
        Route::apiResource('sms_logs','SmsLogController')->except(['update','show','store']); // 短信日志
        Route::apiResource('sms_signs','SmsSignController'); // 短信签名

        // 商品分类
        Route::apiResource('goods_classes','GoodsClassController');
        Route::post('/goods_classes/upload/thumb','GoodsClassController@goods_class_upload')->name('goods_classes.goods_class_upload'); // 缩略图上传
        Route::get('/goods_classes/cache/clear','GoodsClassController@clear_cache')->name('goods_classes.clear_cache'); // 缓存清除商品分类

        // 店铺管理
        Route::apiResource('stores','StoreController')->except(['store']);

        // 全国省市区地址
        Route::apiResource('areas','AreaController');
        Route::get('/areas/cache/clear','AreaController@clear_cache')->name('areas.clear_cache'); // 缓存清除行政地址

        // 商品品牌
        Route::apiResource('goods_brands','GoodsBrandController');
        Route::post('/goods_brands/upload/thumb','GoodsBrandController@goods_brand_upload')->name('goods_brands.goods_brand_upload'); // 品牌缩略图上传

        // 商品管理
        Route::apiResource('goods','GoodsController')->except(['store']);

        // 广告位管理
        Route::apiResource('adv_positions','AdvPositionController');
        Route::apiResource('advs','AdvController');
        Route::post('/advs/upload/thumb','AdvController@adv_upload')->name('advs.adv_upload'); // 缩略图上传

        // 订单管理 
        Route::apiResource('orders','OrderController')->except(['store']);

        // 积分订单管理 
        Route::apiResource('integral_orders','IntegralOrderController')->except(['store','destroy']);

        // 订单评论
        Route::apiResource('order_comments','OrderCommentController')->except(['store','update']);

        // 分销
        Route::apiResource('distribution_logs','DistributionLogController')->only(['index']); // 分销日志

        // 结算日志
        Route::apiResource('order_settlements','OrderSettlementController')->except(['update','destroy']);

        // 资金日志
        Route::apiResource('money_logs','MoneyLogController')->only(['index']);

        // 资金提现
        Route::apiResource('cashes','CashController')->except(['destroy','show']);

        // 积分商城
        Route::apiResource('integral_goods_classes','IntegralGoodsClassController'); // 积分商品栏目
        Route::apiResource('integral_goods','IntegralGoodsController'); // 积分商品
        Route::post('/integral_goods/upload/images','IntegralGoodsController@goods_upload')->name('integral_goods.goods_upload'); // 图片上传
        Route::apiResource('integral_orders','IntegralOrderController'); // 积分订单

        // 数据统计
        Route::get('/statistics/all','StatisticController@all')->name('statistics.all'); // 所有
        Route::get('/statistics/user','StatisticController@user')->name('statistics.user'); // 用户
        Route::get('/statistics/store','StatisticController@store')->name('statistics.store'); // 店铺
        Route::get('/statistics/order','StatisticController@order')->name('statistics.order'); // 订单
        Route::get('/statistics/pay','StatisticController@pay')->name('statistics.pay'); // 支付

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

        // 编辑器上传图片接口
        Route::post('/editor/upload','EditorController@editor'); 

        // 商品品牌
        Route::apiResource('goods_brands','GoodsBrandController')->except(['update','show','store','destroy']);

        // 订单管理 
        Route::apiResource('orders','OrderController')->except(['store','destroy']);

        // 订单评论
        Route::apiResource('order_comments','OrderCommentController')->except(['store','destroy']);

        // 订单售后
        Route::apiResource('refunds','RefundController')->except(['index','store','destroy']);

        // 物流公司
        Route::apiResource('expresses','ExpressController')->except(['update','store','destroy']);

        // 运费配置
        Route::apiResource('freights','FreightController')->except(['show','update']);

        // 资金提现
        Route::apiResource('cashes','CashController')->except(['update','show','destroy']);

        // 分销管理
        Route::apiResource('distributions','DistributionController');
        Route::get('distributions/goods/get_distribution_goods','DistributionController@get_distribution_goods');
        Route::apiResource('distribution_logs','DistributionLogController')->only(['index']); // 分销日志

        // 优惠券
        Route::apiResource('coupons','CouponController');
        Route::apiResource('coupon_logs','CouponLogController')->only(['index']); // 优惠券日志

        // 满减
        Route::apiResource('full_reductions','FullReductionController');

        // 秒杀
        Route::apiResource('seckills','SeckillController');
        Route::get('seckills/goods/get_seckill_goods','SeckillController@get_seckill_goods'); // 获取商品列表

        // 拼团
        Route::apiResource('collectives','CollectiveController');
        Route::get('collectives/goods/get_collective_goods','CollectiveController@get_collective_goods'); // 获取商品列表
        Route::apiResource('collective_logs','CollectiveLogController')->only(['index']); // 拼团日志


        // 结算日志
        Route::apiResource('order_settlements','OrderSettlementController')->only(['index','show']);

        // 店铺资金日志
        Route::apiResource('money_logs','MoneyLogController')->only(['index']);

        // 商家配置
        Route::get('configs','ConfigController@show');
        Route::put('configs','ConfigController@update'); // 修改
        Route::post('configs/upload/images','ConfigController@config_upload'); // 配置上传图片

        // 全国省市区地址获取
        Route::get('/areas','AreaController@areas'); // 商家状态

        // 数据统计
        Route::get('/statistics/all','StatisticController@all'); // 所有
        Route::get('/statistics/order','StatisticController@order'); // 订单

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
    Route::get('/goods/comment_statistics/{id}','GoodsController@goods_comment_statistics'); // 获取商品评论统计
    Route::get('/goods/comments/{id}','GoodsController@goods_comments'); // 获取商品评论列表
    Route::post('/goods/search/all','GoodsController@goods_search'); // 搜索商品列表
    Route::get('/goods/collection/banner','GoodsController@collection_banner'); // 拼团幻灯片

    // 购物车
    Route::get('/carts/cart_count','CartController@cart_count'); // 获取购物车商品数量

    // 店铺列表
    Route::get('/store','StoreController@index'); // 首页店铺列表
    Route::get('/store/{id}','StoreController@get_store_info'); // 店铺首页
    Route::get('/store/{id}/goods','StoreController@get_store_goods_list'); // 店铺首页商品列表

    // 积分商城
    Route::get('/integral','IntegralController@index'); // 首页数据
    Route::get('/integral/goods/{id}','IntegralController@show'); // 积分商品详情
    Route::get('/integral/goods_class','IntegralController@get_integral_class'); // 积分商品分类
    Route::post('/integral/search','IntegralController@search'); // 积分商品列表
    Route::post('/integral/pay','IntegralController@pay'); // 积分商品支付

    // 收藏/关注
    Route::get('/favorites/{id}','FavoriteController@show');

    // 秒杀页面
    Route::get('/seckills','SeckillController@index'); // 首页数据

    Route::group(['middleware'=>'jwt.user'],function(){
        // 购物车
        Route::apiResource('carts','CartController')->except(['show']);

        // 优惠券
        Route::get('/coupons','CouponController@index'); // 优惠券列表
        Route::post('/coupons/receive','CouponController@receive_coupon'); // 领取优惠券

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
        Route::apiResource('favorites','FavoriteController')->except(['update','show']);

        // 资金日志
        Route::apiResource('money_logs','MoneyLogController')->except(['update','show','store','destroy']);

        // 分销日志
        Route::apiResource('distribution_logs','DistributionLogController')->only(['index']);
        Route::get('distribution/user','DistributionLogController@user');
        Route::get('distribution/link','DistributionLogController@link');

        // 资金提现
        Route::apiResource('cashes','CashController')->except(['update','show','destroy']);

        // 订单列表
        Route::get('/order','OrderController@get_orders'); // 获取订单列表

        // 订单售后
        Route::apiResource('refunds','RefundController')->except(['index','destroy']);

        // 订单处理
        Route::get('/order/create_order_before','OrderController@create_order_before'); // 生成订单前处理
        Route::get('/order/create_order_after','OrderController@create_order_after'); // 生成订单后处理
        Route::post('/order/create_order','OrderController@create_order'); // 生成订单
        Route::post('/order/pay','OrderController@pay'); // 订单支付
        Route::post('/order/wechat_pay_check','OrderController@wechat_pay_check'); // 微信支付验证
        Route::put('/order/edit_order_status','OrderController@edit_order_status'); // 取消订单
        Route::get('/order/get_order_info/{id}','OrderController@get_order_info'); // 查看订单信息

        // 积分订单
        Route::get('/integral_order','IntegralController@get_orders'); // 获取积分订单列表
        Route::get('/integral_order/get_order_info/{id}','IntegralController@get_order_info'); // 查看积分订单信息

        // 评论管理
        Route::apiResource('order_comments','OrderCommentController')->except(['destroy']);
        Route::post('/order_comments/thumb/upload','OrderCommentController@comment_upload'); // 评论管理图片上传

        // 商家入驻
        Route::get('/store/join/store_verify','StoreController@store_verify'); // 商家状态
        Route::match(['get','post'],'/store/join/store_join','StoreController@store_join');// 商家入驻
        Route::post('/store/join/upload','StoreController@store_join_upload'); // 商家入驻图片上传

        // 全国省市区地址获取
        Route::get('/areas','AreaController@areas'); 

        // 帮助中心文章获取
        Route::get('/article/{ename}','ArticleController@show'); 

        
    });


    
});


/**
 * 
 * @author hg <364825702@qq.com>
 * 在线聊天 路由 ['middleware'=>'jwt.user'],
 * 
 */
Route::namespace('Chat')->prefix('Chat')->middleware('jwt.user')->group(function(){

    Route::get('/friends','IndexController@friends'); // 
    Route::post('/add_friend','IndexController@add_friend'); // 
    Route::post('/chat_msg','IndexController@chat_msg'); // 
    Route::post('/read_msg','IndexController@read_msg'); // 
    Route::post('/chat_event','IndexController@chat_event'); // 
    Route::post('/image','IndexController@image'); // 

});
Route::namespace('Chat')->prefix('Seller')->middleware('jwt.user')->group(function(){
    // 商家端'
    Route::get('/chat_friends','SellerChatController@friends'); // 
    Route::post('/chat_msg','SellerChatController@chat_msg'); // 
    Route::post('/chat_read_msg','SellerChatController@read_msg'); // 
    Route::post('/chat_event','SellerChatController@chat_event'); // 
});



/**
 * 
 * @author hg <364825702@qq.com>
 * 商城支付回调|其他回调 路由
 * 
 */
Route::namespace('PayCallBack')->group(function(){

    Route::any('/payment/{name}','PaymentController@payment'); // 回调地址  [/api/payment/wechat] | [/api/payment/ali]
    Route::any('/oauth/{name}','OauthController@oauth'); // Oauth 第三方登录  [/api/oauth/wechat] | [/api/payment/github]
    Route::any('/oauth/callback/{name}','OauthController@oauthCallback'); // Oauth 第三方登录回调地址  [/api/oauth/wechat] | [/api/payment/github]
    
});