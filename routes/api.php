<?php

use Illuminate\Http\Request;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

/**
 * 
 * @author hg <364825702@qq.com>
 * 商城总后台 路由
 * 
 */

Route::namespace('Admin')->prefix('Admin')->group(function(){

	// 后台首页获取权限内 栏目
	Route::match(['get','post'],'/users/get_permission_menus','UsersController@get_permission_menus');
    Route::post('/menus/get_bread_nav','MenusController@get_bread_nav');
    
    // 首页统计数据
    Route::get('/index/get_statistics','IndexController@get_statistics');

	// 注册账号
	Route::get('/register','LoginController@register');
	Route::post('/login','LoginController@login');
	Route::get('/get_user_info','UsersController@get_user_info');
	Route::get('/logout','LoginController@logout'); // 注销账号
	Route::get('/check_user_login','LoginController@check_user_login'); // 验证是否登录

	// 后台菜单
	Route::get('/menus/index','MenusController@index');
	Route::match(['get','post'],'/menus/add','MenusController@add');
	Route::match(['get','post'],'/menus/edit/{id}','MenusController@edit');
	Route::post('/menus/del','MenusController@del');

	// 后台角色
	Route::get('/roles/index','RolesController@index');
	Route::match(['get','post'],'/roles/add','RolesController@add');
	Route::match(['get','post'],'/roles/edit/{id}','RolesController@edit');
	Route::post('/roles/del','RolesController@del');

	// 后台钩子
	Route::get('/hooks/index','HooksController@index');
	Route::match(['get','post'],'/hooks/add','HooksController@add');
	Route::match(['get','post'],'/hooks/edit/{id}','HooksController@edit');
	Route::post('/hooks/del','HooksController@del');

	// 后台用户
	Route::get('/users/index','UsersController@index');
	Route::match(['get','post'],'/users/add','UsersController@add');
	Route::match(['get','post'],'/users/edit/{id}','UsersController@edit');
	Route::post('/users/del','UsersController@del');
    Route::get('/users/get_user_info','UsersController@get_user_info'); // 获取用户信息
    Route::post('/users/change_money','UsersController@change_money');

	// 设置中心
	Route::match(['get','post'],'/config/web_config','ConfigController@web_config'); // 站点设置
	Route::match(['get','post'],'/config/upload_config','ConfigController@upload_config'); // 上传设置
	Route::match(['get','post'],'/config/map_config','ConfigController@map_config'); // 地图设置
	Route::match(['get','post'],'/config/wxpay_h5_config','ConfigController@wxpay_h5_config'); // 微信支付H5配置
	Route::match(['get','post'],'/config/wxpay_app_config','ConfigController@wxpay_app_config'); // 微信支付APP配置
	Route::match(['get','post'],'/config/wxpay_jsapi_config','ConfigController@wxpay_jsapi_config'); // 微信支付JSAPI配置
	Route::match(['get','post'],'/config/wxpay_mini_config','ConfigController@wxpay_mini_config'); // 微信支付MINI配置(小程序)
	Route::match(['get','post'],'/config/freight_config','ConfigController@freight_config'); // 阿里云物流配置

    Route::match(['get','post'],'/config/wechat_public_config','ConfigController@wechat_public_config'); // 微信公众号配置
    Route::match(['get','post'],'/config/oauth_config','ConfigController@oauth_config'); // 微信Oauth 配置 PC
    Route::match(['get','post'],'/config/distribution_config','ConfigController@distribution_config'); // 分销配置
    Route::match(['get','post'],'/config/task_time_config','ConfigController@task_time_config'); // 定时任务 自动执行
    
    // 短信列表
    Route::match(['get','post'],'/sms_log/index','SmsLogController@index'); 
    Route::post('/sms_log/del','SmsLogController@del');

	
	Route::match(['get','post'],'/config/alipay_h5_config','ConfigController@alipay_h5_config'); // 支付宝支付 H5
	Route::match(['get','post'],'/config/alipay_app_config','ConfigController@alipay_app_config'); // 支付宝支付 App
	Route::match(['get','post'],'/config/alipay_pc_config','ConfigController@alipay_pc_config'); // 支付宝支付 PC

	Route::match(['get','post'],'/config/alisms_config','ConfigController@alisms_config'); // 支付宝短信sms

	Route::match(['get','post','options'],'/config/logo_upload','ConfigController@logo_upload'); // 站点设置 Logo上传

	// 商品分类
	Route::match(['get','post'],'/goods_class/index','GoodsClassController@index'); 
	Route::match(['get','post'],'/goods_class/add','GoodsClassController@add');
	Route::match(['get','post'],'/goods_class/edit/{id}','GoodsClassController@edit');
	Route::post('/goods_class/del','GoodsClassController@del');
    Route::match(['get','post','options'],'/goods_class/goods_class_upload','GoodsClassController@goods_class_upload'); // 分类图片 上传
    
    // 订单管理
	Route::match(['get','post'],'/order/index','OrderController@index'); 
	Route::match(['get','post'],'/order/info','OrderController@info'); 

	// 品牌分类
	Route::match(['get','post'],'/goods_brand/index','GoodsBrandController@index'); 
	Route::match(['get','post'],'/goods_brand/add','GoodsBrandController@add');
	Route::match(['get','post'],'/goods_brand/edit/{id}','GoodsBrandController@edit');
	Route::post('/goods_brand/del','GoodsBrandController@del');
	Route::match(['get','post','options'],'/goods_brand/goods_brand_upload','GoodsBrandController@goods_brand_upload'); // 品牌图片 上传

	// 地区管理
	Route::match(['get','post'],'/area/index','AreaController@index'); 
	Route::match(['get','post'],'/area/add','AreaController@add');
	Route::match(['get','post'],'/area/edit/{id}','AreaController@edit');
	Route::post('/area/del','AreaController@del');
	Route::post('/area/get_area_children','AreaController@get_area_children');
	Route::get('/area/get_area_list','AreaController@get_area_list');

	// 缓存清空
	Route::get('/auto/cache_flush','AutoController@cache_flush'); 

	// 商品管理
	Route::match(['get','post'],'/goods/index','GoodsController@index'); 
	Route::match(['get','post'],'/goods/goods_verify','GoodsController@goods_verify'); 
	Route::match(['get','post'],'/goods/goods_verify_change','GoodsController@goods_verify_change'); 
	Route::post('/goods/goods_status','GoodsController@goods_status'); //指定ID 修改上下架
	Route::post('/goods/goods_index','GoodsController@goods_index'); //指定ID 修改首页推荐PC

	// 广告位
	Route::match(['get','post'],'/adv_position/index','AdvPositionController@index'); 
	Route::match(['get','post'],'/adv_position/add','AdvPositionController@add');
	Route::match(['get','post'],'/adv_position/edit/{id}','AdvPositionController@edit');
	Route::post('/adv_position/del','AdvPositionController@del');

	// 广告
	Route::match(['get','post'],'/adv/index','AdvController@index'); 
	Route::match(['get','post'],'/adv/add','AdvController@add');
	Route::match(['get','post'],'/adv/edit/{id}','AdvController@edit');
	Route::post('/adv/del','AdvController@del');
    Route::match(['get','post','options'],'/adv/adv_upload','AdvController@adv_upload'); // 广告图片 上传

    // 秒杀
	Route::match(['get','post'],'/seckill/index','SeckillController@index'); 
	Route::match(['get','post'],'/seckill/add','SeckillController@add');
	Route::match(['get','post'],'/seckill/edit/{id}','SeckillController@edit');
    Route::post('/seckill/del','SeckillController@del');
    Route::post('/seckill/get_add_seckill_goods','SeckillController@get_add_seckill_goods');
    Route::post('/seckill/change_status','SeckillController@change_status');
    Route::post('/seckill/del_seckill_goods','SeckillController@del_seckill_goods');
    
    // 站点协议
    Route::match(['get','post'],'/agreement/index','AgreementController@index'); 
	Route::match(['get','post'],'/agreement/add','AgreementController@add');
	Route::match(['get','post'],'/agreement/edit/{id}','AgreementController@edit');
    Route::post('/agreement/del','AgreementController@del');
    
    // 店铺列表
    Route::match(['get','post'],'/store/index','StoreController@index'); 
	Route::post('/store/del','StoreController@del');
    Route::post('/store/store_pass','StoreController@store_pass');
    
    // 积分商城
	Route::match(['get','post','options'],'/integral/integral_upload','IntegralController@integral_upload'); // 积分商品图片 上传
	Route::match(['get','post'],'/integral/index','IntegralController@index'); 
	Route::match(['get','post'],'/integral/add','IntegralController@add');
	Route::match(['get','post'],'/integral/edit/{id}','IntegralController@edit');
	Route::post('/integral/del','IntegralController@del');
    Route::post('/integral/goods_status','IntegralController@goods_status'); //指定ID 修改上下架
    Route::post('/integral/goods_hot','IntegralController@goods_hot'); //指定ID 修改热门推荐

    // 积分商城分类
	Route::match(['get','post'],'/integral_class/index','IntegralClassController@index'); 
	Route::match(['get','post'],'/integral_class/add','IntegralClassController@add');
	Route::match(['get','post'],'/integral_class/edit/{id}','IntegralClassController@edit');
    Route::post('/integral_class/del','IntegralClassController@del');
    
    // 积分订单管理
	Route::match(['get','post'],'/integral_order/index','IntegralOrderController@index'); 
	Route::match(['get','post'],'/integral_order/info','IntegralOrderController@info'); 
    Route::match(['get','post'],'/integral_order/send_delivery','IntegralOrderController@send_delivery');  // 填写物流
    
    // 资金提现
    Route::match(['get','post'],'/cash/index','CashLogController@index'); 
	Route::match(['get','post'],'/cash/change_status','CashLogController@change_status');
	Route::post('/cash/del','CashLogController@del');
});



/**
 * 
 * @author hg <364825702@qq.com>
 * 商家后台 路由
 * 
 */

Route::namespace('Seller')->prefix('Seller')->group(function(){

	Route::post('/login','LoginController@login');
	Route::get('/get_user_info','UsersController@get_user_info');
    Route::get('/check_user_login','LoginController@check_user_login'); // 验证是否登录
    
    // 首页统计数据
    Route::get('/index/get_statistics','IndexController@get_statistics');

	// 规格属性
	Route::match(['get','post'],'/attr_spec/index','AttrSpecController@index'); 
	Route::match(['get','post'],'/attr_spec/add','AttrSpecController@add');
	Route::match(['get','post'],'/attr_spec/edit/{id}','AttrSpecController@edit');
    Route::post('/attr_spec/del','AttrSpecController@del');
    
    // 商家自定义分类
	Route::match(['get','post'],'/store_goods_class/index','StoreGoodsClassController@index'); 
	Route::match(['get','post'],'/store_goods_class/add','StoreGoodsClassController@add');
	Route::match(['get','post'],'/store_goods_class/edit/{id}','StoreGoodsClassController@edit');
	Route::post('/store_goods_class/del','StoreGoodsClassController@del');

	// 商品
	Route::match(['get','post','options'],'/goods/goods_upload','GoodsController@goods_upload'); // 商品图片 上传
	Route::match(['get','post'],'/goods/index','GoodsController@index'); 
	Route::match(['get','post'],'/goods/add','GoodsController@add');
	Route::match(['get','post'],'/goods/edit/{id}','GoodsController@edit');
	Route::post('/goods/del','GoodsController@del');
    Route::post('/goods/goods_status','GoodsController@goods_status'); //指定ID 修改上下架

    // 订单管理
	Route::match(['get','post'],'/order/index','OrderController@index'); 
	Route::match(['get','post'],'/order/info','OrderController@info'); 
    Route::post('/order/send_delivery','OrderController@send_delivery'); 
    Route::post('/order/refund','OrderController@refund'); 
    Route::post('/order/refund_money','OrderController@refund_money'); 
    
    // 拼团订单
    Route::match(['get','post'],'/groupbuy/index','GroupbuyController@index'); 
    Route::post('/groupbuy/get_groupbuy_user','GroupbuyController@get_groupbuy_user'); 

    // 运费模版
	Route::match(['get','post'],'/freight_template/index','FreightTemplateController@index'); 
	Route::match(['get','post'],'/freight_template/add','FreightTemplateController@add');
	Route::match(['get','post'],'/freight_template/edit/{id}','FreightTemplateController@edit');
	Route::post('/freight_template/del','FreightTemplateController@del');

    // 添加商品 选择商品分类
    Route::post('/store/get_store_class','StoreController@get_store_class'); 
    
    // 商家店铺信息设置
    Route::match(['get','post'],'/store/setting','StoreController@setting');
    Route::match(['get','post'],'/free_freight/setting','StoreController@free_freight'); // 设置包邮
    Route::match(['get','post'],'/store/after_sale_content','StoreController@after_sale_content'); // 设置包邮
    Route::match(['get','post','options'],'/store/logo_upload','StoreController@logo_upload'); 

    // 秒杀
	Route::match(['get','post'],'/seckill/index','SeckillController@index'); 
	Route::match(['get','post'],'/seckill/add','SeckillController@add');
    Route::post('/seckill/add_seckill_goods','SeckillController@add_seckill_goods');
    Route::post('/seckill/get_add_seckill_goods','SeckillController@get_add_seckill_goods');
    Route::post('/seckill/del_seckill_goods','SeckillController@del_seckill_goods');
    

    Route::get('/area/get_area_list','AreaController@get_area_list'); // 获取地区信息  省市区  三级选择框



	// 富文本编辑上传
	Route::match(['get','post','options'],'/auto/auto_upload','AutoController@auto_upload'); 

});


/**
 * 
 * @author hg <364825702@qq.com>
 * PC端电商 路由
 * 
 */

Route::namespace('Home')->group(function(){

	Route::post('/user/login','LoginController@login');
	Route::get('/user/logout','LoginController@logout');
	Route::get('/user/get_user_info','UsersController@get_user_info'); // 获取用户信息
	Route::get('/check_user_login','LoginController@check_user_login'); // 验证是否登录
	Route::get('/user/get_oauth_config','LoginController@get_oauth_config'); // 获取Oauth 配置
	Route::post('/user/register','LoginController@register'); // 注册
    Route::post('/user/send_sms','LoginController@send_sms'); // 用户登录注册发送短信
    Route::post('/user/send_email','LoginController@send_email'); // 用户登录注册发送短信

    // 用户认证
    Route::get('/user/get_user_check_info','UsersController@get_user_check_info'); // 获取用户认证信息
    Route::match(['get','post'],'/user/edit_user_check_info','UsersController@edit_user_check_info'); // 修改用户认证信息
    Route::match(['get','post','options'],'/user/user_card','UsersController@user_card');  // 头像上传

    // 商城首页接口
    Route::get('/index/get_subnav_info','IndexController@get_subnav_info'); // 首页左导航信息接口
    Route::get('/index/get_index_info','IndexController@get_index_info'); // 首页信息接口
    Route::get('/index/get_foot_info','IndexController@get_foot_info'); // PC公共底部信息

    // 获取首页秒杀商品
    Route::get('/index/get_seckill_goods','IndexController@get_seckill_goods'); 

    // 秒杀专题页面列表
    Route::post('/seckill/get_seckill_list','SeckillController@get_seckill_list'); 

    // 商品详情
    Route::post('/goods/get_goods_info','GoodsController@get_goods_info'); 
    Route::post('/goods/search_goods','GoodsController@search_goods');  // 搜索商铺 或者条件列取
    Route::get('/goods/get_brand_list','GoodsController@get_brand_list');  // 获取品牌信息
    Route::post('/goods/get_comment_list_by_goods','StoreCommentController@get_comment_list_by_goods');  // 获取评论列表根据商品ID

    // 收藏接口
    Route::post('/fav/is_fav','UsersController@is_fav');  // 判断是否收藏
    Route::post('/fav/edit_fav','UsersController@edit_fav');  // 修改收藏状态

    // 用户中心
    Route::get('/user/get_user_default','UsersController@get_user_default');  // 默认页面信息
    Route::post('/user/get_user_order','UsersController@get_user_order');  // 用户中心订单
    Route::match(['get','post'],'/user/edit_user_info','UsersController@edit_user_info');  // 修改用户信息
    Route::post('/user/get_fav_list','UsersController@get_fav_list');  // 收藏和关注
    Route::post('/user/check_pay_password','UsersController@check_pay_password');  // 验证支付密码
    Route::post('/user/del_fav','UsersController@del_fav');  // 删除收藏关注
    Route::post('/user/get_money_log','MoneyLogController@get_money_log');  // 获取账号资金变更日志
    Route::match(['get','post','options'],'/user/avatar','UsersController@avatar');  // 头像上传

    // 邀请码生成
    Route::get('/user/get_inviter_info','UsersController@get_inviter_info');  // 默认页面信息

    // 商家首页
    Route::post('/store/get_store_info','StoreController@get_store_info');  // 获取商家信息
    Route::post('/store/get_store_index_info','StoreController@get_store_index_info');  // 获取商家首页信息
    Route::post('/store/get_store_class','StoreController@get_store_class');  // 获取商家分类信息
    Route::post('/store/get_store_class_goods','StoreController@get_store_class_goods');  // 获取商家分类商品

    // 销售排行列表
    Route::post('/goods/get_sale_list','GoodsController@get_sale_list'); 

    // 购物车
    Route::post('/cart/add_cart','CartController@add_cart');  // 加入购物车
    Route::post('/cart/change_cart','CartController@change_cart');  // 购物车数据修改
    Route::post('/cart/del_cart','CartController@del_cart');  // 购物车数据删除
    Route::get('/cart/get_cart_list','CartController@get_cart_list');  // 购物车数据获取
    Route::get('/cart/get_cart_count','CartController@get_cart_count');  // 购物车数据数量

    // 用户收货地址
    Route::get('/address/index','AddressController@index'); 
	Route::post('/address/add','AddressController@add');
	Route::match(['get','post'],'/address/edit/{id}','AddressController@edit');
	Route::post('/address/del','AddressController@del');
    Route::post('/address/set_default','AddressController@set_default');  // 设置默认地址
    
    // 预生成订单数据
    Route::post('/order/get_befor_order','OrderController@get_befor_order');  

    // 生成订单
    Route::post('/order/create_order','OrderController@create_order');  
    Route::post('/order/get_order_info_by_order_no','OrderController@get_order_info_by_order_no');  // 获取订单信息根据订单号 Array 是个数组

    // 评论区
    Route::post('/order/get_order_info_by_order_id','OrderController@get_order_info_by_order_id');  // 获取订单信息根据ID
    Route::post('/order/get_comment_list','StoreCommentController@get_comment_list');  // 获取订单信息根据ID
    Route::post('/order/add_comment','StoreCommentController@add_comment');  // 添加评论

    Route::match(['get','post','options'],'/comment/comment_image','StoreCommentController@comment_image'); // 评论图片

    // 获取订单物流
    Route::get('/order/get_user_delivery','UsersController@get_user_delivery'); 
    Route::post('/order/change_order_status','UsersController@change_order_status');  // 修改订单状态

    // 订单取消
    Route::post('/order/close_order','OrderController@close_order'); 
    Route::post('/order/refund','OrderController@refund'); // 申请售后
    Route::post('/order/refund_delivery_no','OrderController@refund_delivery_no'); // 售后发送快递

    // 支付
    Route::post('/order/pay','PayController@pay');  
    Route::post('/order/check_wxpay_native','PayController@check_wxpay_native'); // 查下微信订单是否支付成功; 仅限扫码  
    // Route::get('/order/qr_show/{code_url}','PayController@qr_show');  

	// 无压缩裁剪上传
    Route::match(['get','post','options'],'/auto/auto_upload','AutoController@auto_upload'); 
    
    // 入驻商家
    Route::match(['get','post'],'/user/join','StoreJoinController@join');
    Route::get('/index/get_join_index_adv','IndexController@get_join_index_adv');  // 获取加入我们幻灯片
    Route::get('/store/is_store','StoreJoinController@is_store');  // 判断是否是商家  // 返回商家的用户账号
    
    // 积分商城 首页信息
    Route::get('/integral/get_index_info','IntegralController@get_index_info');
    Route::get('/integral/goods/info/{id}','IntegralController@get_integral_goods_info'); // 积分商品详情
    Route::post('/integral/goods/search_integral_goods','IntegralController@search_integral_goods'); // 搜索积分产品列表
    Route::get('/integral/goods/get_integral_goods_class','IntegralController@get_integral_goods_class'); // 搜索积分产品列表

    // 积分生成订单
    Route::post('/integral/order/create_order','IntegralOrderController@create_order'); // 搜索积分产品列表
    Route::post('/user/integral/get_user_order','IntegralOrderController@get_user_order'); // 获取用户的积分订单列表
    Route::post('/user/integral/change_order_status','IntegralOrderController@change_order_status'); // 积分订单确认收货

    // 拼团
    Route::post('/groupbuy/get_groupbuy_banner','GroupbuyController@get_groupbuy_banner'); 

    // 提现
    Route::post('/cash/get_cash_log','CashLogController@get_cash_log'); 
    Route::match(['get','post'],'/cash/add_cash','CashLogController@add_cash'); 


    // 在线支付回调接口
    Route::match(['get','post'],'/payment/alipay','PayMentController@alipay'); // 支付宝支付
    Route::match(['get','post'],'/payment/wxpay','PayMentController@wxpay'); // 微信支付


    // ---------------------  公共接口 ------------------------- //

    // 根据英文名获取协议内容
    Route::post('/api/get_agreement_info','ApiController@get_agreement_info');

    // 获取地区信息
    Route::get('/api/get_area_list','ApiController@get_area_list');

    // 获取商品分类信息
    Route::get('/api/get_goods_class_list','ApiController@get_goods_class_list');

    // 获取幻灯片 or 广告
    Route::post('/api/get_banner','ApiController@get_banner');


});



// 聊天接口
Route::namespace('chat')->group(function(){

    // 添加为好友
    Route::post('/chat/add_friend','IndexController@add_friend');

    Route::get('/chat/get_chat_friend','IndexController@get_chat_friend'); // 获取好友列表 

    Route::post('/chat/get_chat_msg','IndexController@get_chat_msg'); // 获取聊天信息

    Route::post('/chat/chat_event','IndexController@chat_event');// 接收前端页面的信息接口

    Route::post('/chat/read_msg','IndexController@read_msg');// 处理消息为已读

    Route::match(['get','post','options'],'/chat/image','IndexController@image'); 


});