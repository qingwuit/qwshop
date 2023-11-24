<?php

use Illuminate\Support\Facades\Route;

// 用户相关
Route::get('/common', [App\Http\Controllers\Home\IndexController::class,'common'])->name('home.config.common');
Route::get('/home', [App\Http\Controllers\Home\IndexController::class,'home'])->name('home.common.index'); // 首页信息
Route::get('/stores', [App\Http\Controllers\Home\StoresController::class,'stores'])->name('home.stores.index'); // 店铺列表
Route::get('/store/{id}', [App\Http\Controllers\Home\StoresController::class,'show'])->name('home.store.show'); // 无权限获取店铺信息
Route::get('/goods', [App\Http\Controllers\Home\GoodsController::class,'goods'])->name('home.goods.index'); // 商品列表 | 搜索
Route::get('/goods/{id}', [App\Http\Controllers\Home\GoodsController::class,'show'])->name('home.goods.show'); // 商品详情
Route::get('/goods_comments/{id}', [App\Http\Controllers\Home\GoodsController::class,'goods_comments'])->name('home.goods.comments'); // 商品评论
Route::get('/is_fav', [App\Http\Controllers\Home\FavoritesController::class,'is_fav'])->name('home.user.isfav'); // 是否收藏关注
Route::get('/cart_count', [App\Http\Controllers\Home\CartsController::class,'count'])->name('home.cart.count'); // 统计购物车数量
Route::get('/integral/home', [App\Http\Controllers\Home\IntegralController::class,'home'])->name('home.integral.home'); // 积分商城
Route::get('/integral/class', [App\Http\Controllers\Home\IntegralController::class,'integral_class'])->name('home.integral.class');
Route::get('/integral/goods', [App\Http\Controllers\Home\IntegralController::class,'index'])->name('home.integral.goods');
Route::get('/integral/goods/{id}', [App\Http\Controllers\Home\IntegralController::class,'show'])->name('home.integral.show');
Route::get('/seckills', [App\Http\Controllers\Home\SeckillsController::class,'index'])->name('home.seckills.index');
Route::get('/adv',[App\Http\Controllers\Home\AdvSpacesController::class,'index'])->name('home.advspaces.index'); // 获取广告幻灯片




Route::middleware('auth:users')->group(function () {
    Route::post('/auth/info', [App\Http\Controllers\Auth\AuthController::class,'info'])->name('home.auth.info');
    Route::post('/auth/edit', [App\Http\Controllers\Auth\AuthController::class,'edit'])->name('home.auth.edit');

    // 入驻
    Route::post('/store/join', [App\Http\Controllers\Home\StoresController::class,'join'])->name('home.store.join');
    Route::put('/store', [App\Http\Controllers\Home\StoresController::class,'edit'])->name('home.store.edit');
    Route::get('/store', [App\Http\Controllers\Home\StoresController::class,'info'])->name('home.store.info');

    Route::get('/user/default', [App\Http\Controllers\Home\IndexController::class,'default']); // 用户首页
    Route::resource('/user/addresses', App\Http\Controllers\Home\AddressesController::class); // 收货地址
    Route::get('/user/addresses/default/{id}', [App\Http\Controllers\Home\AddressesController::class,'set_default'])->name('home.address.default');
    Route::resource('/user/comments', App\Http\Controllers\Home\CommentsController::class)->only(['update','index','store','show']);
    Route::put('/auth/edit', [App\Http\Controllers\Auth\AuthController::class,'edit'])->name('home.auth.edit2'); // 编辑用户信息
    Route::post('/user/check', [App\Http\Controllers\Home\UserChecksController::class,'edit'])->name('home.user.check.edit');
    Route::get('/user/check', [App\Http\Controllers\Home\UserChecksController::class,'check'])->name('home.user.check');
    Route::resource('/user/cashes', App\Http\Controllers\Home\CashesController::class)->only(['store','index']); // 提现
    Route::resource('/user/favorites', App\Http\Controllers\Home\FavoritesController::class)->only(['store','index','destroy']);
    Route::resource('/user/money_logs', App\Http\Controllers\Home\MoneyLogsController::class)->only(['index']);
    Route::resource('/user/carts', App\Http\Controllers\Home\CartsController::class)->except(['show']);
    Route::get('/user/orders', [App\Http\Controllers\Home\OrdersController::class,'orders'])->name('home.orders');
    Route::put('/user/order/{id}', [App\Http\Controllers\Home\OrdersController::class,'edit'])->name('home.orders.edit');
    Route::get('/user/order/{id}', [App\Http\Controllers\Home\OrdersController::class,'show'])->name('home.orders.show');
    Route::post('/user/order/before', [App\Http\Controllers\Home\OrdersController::class,'before'])->name('home.order.before');
    Route::post('/user/order/create', [App\Http\Controllers\Home\OrdersController::class,'create'])->name('home.order.create');
    Route::post('/user/order/after', [App\Http\Controllers\Home\OrdersController::class,'after'])->name('home.order.after');
    Route::post('/user/order/pay', [App\Http\Controllers\Home\OrdersController::class,'pay'])->name('home.order.pay');
    Route::post('/user/order/check', [App\Http\Controllers\Home\OrdersController::class,'check'])->name('home.order.check');
    Route::post('/user/order/express', [App\Http\Controllers\Home\OrdersController::class,'express'])->name('home.order.express');
    Route::resource('/user/order/refund', App\Http\Controllers\Home\RefundsController::class)->only(['store','show','update']);
    Route::resource('/user/coupons', App\Http\Controllers\Home\CouponsController::class)->only(['index']);
    Route::post('/user/coupon/receive', [App\Http\Controllers\Home\CouponsController::class,'receive'])->name('home.coupon.receive'); // 领取优惠劵
    Route::post('/integral/pay', [App\Http\Controllers\Home\IntegralController::class,'pay'])->name('home.integral.pay');
    Route::get('/integral/orders', [App\Http\Controllers\Home\IntegralController::class,'integral_orders'])->name('home.integral.orders');
    Route::get('/user/distributions', [App\Http\Controllers\Home\DistributionsController::class,'index'])->name('home.distribution.index');
    Route::get('/user/distribution_logs', [App\Http\Controllers\Home\DistributionsController::class,'index'])->name('home.distribution_log.index');
});




// 登录注册
Route::any('/login', [App\Http\Controllers\Auth\AuthController::class,'login']);
Route::any('/logout', [App\Http\Controllers\Auth\AuthController::class,'logout']);
Route::any('/register', [App\Http\Controllers\Auth\AuthController::class,'register']);
Route::post('/forget_password', [App\Http\Controllers\Auth\AuthController::class,'forget_password']);
Route::get('/is_login', [App\Http\Controllers\Auth\AuthController::class,'is_login']); // 检查是否登录
Route::post('/captcha', [App\Http\Controllers\Auth\AuthController::class,'captcha']); // 获取滑动验证码

// 后台接口
Route::get('/load_goods_classes', [App\Http\Controllers\Admin\Exts\GoodsClassesController::class,'loadMenu'])->name('base.loadMenu');
Route::get('/load_areas', [App\Http\Controllers\Admin\Exts\AreasController::class,'loadArea'])->name('base.loadAreas');
Route::get('/expresses', [App\Http\Controllers\Admin\Exts\ExpressesController::class,'index'])->name('base.expresses'); // 物流公司
Route::get('/load_article_menu', [App\Http\Controllers\Admin\ArticleMenusController::class,'loadMenu']);
Route::post('/uploads', [App\Http\Controllers\Home\UploadsController::class,'upload'])->name('home.uploads');
Route::post('/sms', [App\Http\Controllers\Home\SmsController::class,'send'])->name('home.sms'); // 短信发送
Route::get('/article/{name}', [App\Http\Controllers\Home\ArticlesController::class,'article'])->name('home.article'); // 文章
Route::get('/agreements/{name}', [App\Http\Controllers\Home\AgreementsController::class,'agreement'])->name('home.agreement'); // 协议
Route::get('/Admin/load_menu', [App\Http\Controllers\Admin\MenusController::class,'loadMenu']);
Route::get('/app_versions', [App\Http\Controllers\Admin\AppVersionsController::class,'app_versions']); // 获取APP版本
Route::prefix('Admin')->name('admin.')->middleware('auth:admins')->group(function () {
    // Route::get('/load_menu', [App\Http\Controllers\Admin\MenusController::class,'loadMenu']);
    Route::get('load_permission', [App\Http\Controllers\Admin\PermissionsController::class,'loadPermission'])->name('load_permission');
    Route::any('/auth/info', [App\Http\Controllers\Auth\AuthController::class,'info'])->name('auth.info');
    Route::any('/auth/edit', [App\Http\Controllers\Auth\AuthController::class,'edit'])->name('auth.edit');
    Route::resource('admins', App\Http\Controllers\Admin\AdminsController::class);
    Route::resource('users', App\Http\Controllers\Admin\UsersController::class);
    Route::post('users/money/handle', [App\Http\Controllers\Admin\UsersController::class,'money'])->name('users.money.handle');
    Route::resource('menus', App\Http\Controllers\Admin\MenusController::class);
    Route::resource('roles', App\Http\Controllers\Admin\RolesController::class);
    Route::resource('permission_groups', App\Http\Controllers\Admin\PermissionGroupsController::class);
    Route::resource('permissions', App\Http\Controllers\Admin\PermissionsController::class);
    Route::resource('sms', App\Http\Controllers\Admin\SmsController::class);
    Route::resource('sms_logs', App\Http\Controllers\Admin\SmsLogsController::class);
    Route::resource('agreements', App\Http\Controllers\Admin\AgreementsController::class); // 协议
    Route::resource('article_menus', App\Http\Controllers\Admin\ArticleMenusController::class);
    Route::resource('articles', App\Http\Controllers\Admin\ArticlesController::class);
    Route::resource('notices', App\Http\Controllers\Admin\NoticesController::class);
    Route::resource('adv_spaces', App\Http\Controllers\Admin\AdvSpacesController::class);
    Route::resource('advs', App\Http\Controllers\Admin\AdvsController::class);
    Route::resource('app_versions', App\Http\Controllers\Admin\AppVersionsController::class); // 软件升级更新
    Route::resource('money_logs', App\Http\Controllers\Admin\MoneyLogsController::class)->only(['index','show']);
    Route::resource('seller_menus', App\Http\Controllers\Admin\SellerMenusController::class);
    Route::resource('areas', App\Http\Controllers\Admin\Exts\AreasController::class);
    Route::resource('currencies', App\Http\Controllers\Admin\Exts\CurrenciesController::class); // 语种路由
    Route::resource('integral_orders', App\Http\Controllers\Admin\Exts\IntegralOrdersController::class)->only(['index','show','update']);
    Route::get('/clear_area', [App\Http\Controllers\Admin\Exts\AreasController::class,'clear_area'])->name('base.clearArea');
    Route::get('load_seller_menu', [App\Http\Controllers\Admin\SellerMenusController::class,'loadMenu'])->name('load_seller_menu');
    Route::post('sms/send', [App\Http\Controllers\Admin\SmsController::class,'send'])->name('sms'); // 短信发送
    Route::get('configs', [App\Http\Controllers\Admin\ConfigsController::class,'index'])->name('configs');
    Route::put('configs/update', [App\Http\Controllers\Admin\ConfigsController::class,'edit'])->name('configs.update');
    Route::post('uploads', [App\Http\Controllers\Admin\UploadsController::class,'upload'])->name('uploads');

    // 商城相关
    Route::resource('goods_classes', App\Http\Controllers\Admin\Exts\GoodsClassesController::class);
    Route::resource('goods_brands', App\Http\Controllers\Admin\Exts\GoodsBrandsController::class);
    Route::resource('goods', App\Http\Controllers\Admin\Exts\GoodsController::class);
    Route::resource('goods_attrs', App\Http\Controllers\Admin\Exts\GoodsAttrsController::class);
    Route::resource('goods_specs', App\Http\Controllers\Admin\Exts\GoodsSpecsController::class);
    Route::resource('goods_skus', App\Http\Controllers\Admin\Exts\GoodsSkusController::class);
    Route::resource('stores', App\Http\Controllers\Admin\Exts\StoresController::class);
    Route::resource('orders', App\Http\Controllers\Admin\Exts\OrdersController::class);
    Route::resource('cashes', App\Http\Controllers\Admin\Exts\CashesController::class);
    Route::resource('order_comments', App\Http\Controllers\Admin\Exts\OrderCommentsController::class);
    Route::resource('order_settlements', App\Http\Controllers\Admin\Exts\OrderSettlementsController::class)->only(['index','show']); // 结算订单
    Route::get('order_settlement_handle', [App\Http\Controllers\Admin\Exts\OrderSettlementsController::class,'handle_sett'])->name('order.settl.handle'); // 结算订单
    Route::resource('expresses', App\Http\Controllers\Admin\Exts\ExpressesController::class);
    Route::resource('integral_goods_classes', App\Http\Controllers\Admin\Exts\IntegralGoodsClassesController::class);
    Route::resource('integral_goods', App\Http\Controllers\Admin\Exts\IntegralGoodsController::class);
    Route::resource('distribution_logs', App\Http\Controllers\Admin\Exts\DistributionLogsController::class)->only(['index']);
    Route::post('/orders/express/find', [App\Http\Controllers\Admin\Exts\OrdersController::class,'express'])->name('order.express');
    Route::get('/orders/find/all', [App\Http\Controllers\Admin\Exts\OrdersController::class,'all'])->name('order.findall');
    Route::get('/orders/print/waybill/{id}', [App\Http\Controllers\Admin\Exts\OrdersController::class,'print_waybill'])->name('order.print.waybill');
    Route::put('/orders/status/edit', [App\Http\Controllers\Admin\Exts\OrdersController::class,'edit'])->name('order.edit');
    Route::get('/dashboard/all', [App\Http\Controllers\Admin\DashboardController::class,'all'])->name('dashboard.all'); // 仪表盘
    Route::get('/dashboard/order', [App\Http\Controllers\Admin\DashboardController::class,'order'])->name('dashboard.order'); // 数据统计
    Route::get('/dashboard/user', [App\Http\Controllers\Admin\DashboardController::class,'user'])->name('dashboard.user'); // 数据统计
    Route::get('/dashboard/pay', [App\Http\Controllers\Admin\DashboardController::class,'pay'])->name('dashboard.pay'); // 数据统计
    Route::get('/dashboard/store', [App\Http\Controllers\Admin\DashboardController::class,'stores'])->name('dashboard.store'); // 数据统计
});


// 商家路由
Route::prefix('Seller')->name('seller.')->middleware('auth:users')->group(function () {
    Route::any('/auth/info', [App\Http\Controllers\Auth\AuthController::class,'info'])->name('auth.info');
    Route::any('/auth/edit', [App\Http\Controllers\Auth\AuthController::class,'edit'])->name('auth.edit');
    Route::get('/load_menu', [App\Http\Controllers\Seller\MenusController::class,'loadMenu'])->name('load_menu');
    Route::post('uploads', [App\Http\Controllers\Seller\UploadsController::class,'upload'])->name('uploads');
    Route::resource('users', App\Http\Controllers\Seller\UsersController::class);
    Route::resource('roles', App\Http\Controllers\Seller\RolesController::class);

    // 商城相关
    Route::resource('goods', App\Http\Controllers\Seller\GoodsController::class);
    Route::resource('goods_brands', App\Http\Controllers\Seller\GoodsBrandsController::class)->only('index');
    Route::resource('goods_attrs', App\Http\Controllers\Seller\GoodsAttrsController::class);
    // Route::resource('goods_specs',App\Http\Controllers\Seller\GoodsSpecsController::class);
    Route::resource('order_comments', App\Http\Controllers\Seller\OrderCommentsController::class);
    Route::get('store_classes', [App\Http\Controllers\Seller\StoresController::class,'store_classes'])->name('store.classes');
    Route::resource('stores', App\Http\Controllers\Seller\StoresController::class)->except(['store','destroy']);
    Route::resource('money_logs', App\Http\Controllers\Seller\MoneyLogsController::class)->only(['index','show']);
    Route::resource('orders', App\Http\Controllers\Seller\OrdersController::class)->only(['index','show','update']);
    Route::resource('order_settlements', App\Http\Controllers\Seller\OrderSettlementsController::class)->only(['index','show']); // 结算订单
    Route::resource('cashes', App\Http\Controllers\Seller\CashesController::class)->except(['update']);
    Route::resource('freights', App\Http\Controllers\Seller\FreightsController::class)->only(['update','index','destroy']);
    Route::resource('distributions', App\Http\Controllers\Seller\DistributionsController::class);
    Route::resource('distribution_logs', App\Http\Controllers\Seller\DistributionLogsController::class)->only(['index']);
    Route::resource('coupons', App\Http\Controllers\Seller\CouponsController::class);
    Route::resource('coupon_logs', App\Http\Controllers\Seller\CouponLogsController::class)->only(['index']);
    Route::resource('full_reductions', App\Http\Controllers\Seller\FullReductionsController::class);
    Route::resource('collectives', App\Http\Controllers\Seller\CollectivesController::class);
    Route::resource('seckills', App\Http\Controllers\Seller\SeckillsController::class);
    Route::resource('file_space_dirs', App\Http\Controllers\Seller\FileSpaceDirsController::class); // 图片空间文件夹
    Route::resource('file_spaces', App\Http\Controllers\Seller\FileSpacesController::class)->only(['index','destroy']); // 图片空间
    Route::post('/orders/express/find', [App\Http\Controllers\Seller\OrdersController::class,'express'])->name('order.express');
    Route::get('/orders/find/all', [App\Http\Controllers\Seller\OrdersController::class,'all'])->name('order.findall');
    Route::get('/orders/print/waybill/{id}', [App\Http\Controllers\Seller\OrdersController::class,'print_waybill'])->name('order.print.waybill');
    Route::put('/orders/status/edit', [App\Http\Controllers\Seller\OrdersController::class,'edit'])->name('status.edit');
    Route::put('/refunds/{id}', [App\Http\Controllers\Seller\RefundsController::class,'update'])->name('refunds.update');
    Route::get('/dashboard/all', [App\Http\Controllers\Seller\DashboardController::class,'all'])->name('dashboard.all'); // 仪表盘
    Route::get('/dashboard/order', [App\Http\Controllers\Seller\DashboardController::class,'order'])->name('dashboard.order'); // 销售分析
});


/**
 *
 * @author hg <bishashiwo@gmail.com>
 * 在线聊天
 *
 */
Route::prefix('Chat')->namespace('Chat')->group(function () {
    Route::post('/friends', [App\Http\Controllers\Chat\IndexController::class,'friends']);  // 好友列表
    Route::post('/add', [App\Http\Controllers\Chat\IndexController::class,'add']);  // 互相关注好友
    Route::post('/friend_content', [App\Http\Controllers\Chat\IndexController::class,'friend_content']); // 查看聊天内容
    Route::post('/send', [App\Http\Controllers\Chat\IndexController::class,'send']);  // 发送信息
});

/**
 *
 * @author hg <bishashiwo@gmail.com>
 * 商城支付回调|其他回调 路由
 *
 */
Route::namespace('PayCallBack')->group(function () {

    Route::any('/payment/{name}/{device}', [App\Http\Controllers\PayCallBack\PaymentController::class,'payment']); // 回调地址  [/api/payment/wechat/web] | [/api/payment/alipay/mini]
    Route::any('/oauth/{name}', [App\Http\Controllers\PayCallBack\OauthController::class,'oauth']); // Oauth 第三方登录  [/api/oauth/weixin] | [/api/oauth/weixinweb] | [/api/oauth/github]
    Route::any('/oauth/callback/{name}', [App\Http\Controllers\PayCallBack\OauthController::class,'oauthCallback']); // Oauth 第三方登录回调地址  [/api/oauth/callback/weixin|/api/callback/oauth/weixinweb] | [/api/payment/github]
    Route::get('/openid/code', [App\Http\Controllers\PayCallBack\OauthController::class,'getOpenId']); // 根据Code 获取openId 小程序和公众号需要
    Route::get('/wechat/ticket', [App\Http\Controllers\PayCallBack\OauthController::class,'getWechatJsapi']); // 根据token 获取jsapi分享数据
});
