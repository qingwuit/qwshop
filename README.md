## Welcome to Qingwu mall 欢迎使用 青梧商城系统 (B2B2C)
![logo](https://gitee.com/qingwuitcn/qwshop/raw/master/resources/js/asset/pc/logo.png "logo")

> 我们理解您需要一套前后端分离，功能齐全操作简易的商城框架 青梧商城系统 就是秉承着这样的目的开发出来的，或许您是开发人员，又或许您是即将要创业的老板，都可以基于青梧商城系统进行快速项目启动。

## Need environment and existing functions 需要环境和已有功能
- Laravel 7.x + Vue 前后分离
- 多商户、秒杀、团购、优惠券、在线聊天、三级分销、积分商城、Wechat支付、Alipay支付
- 支持二次开发
- PHP >= 7.3

## Documentation 详细文档
[[详细文档] click it](http://doc.qingwuit.com/ "点击它[详细文档]")

## Code address 代码地址
[[Gitee地址] click it](https://gitee.com/qingwuitcn/qwshop "点击它[代码地址]") - [[Github地址] click it](https://github.com/qingwuit/qwshop "点击它[代码地址]")

## Demo address 演示地址
[[演示地址] click it](http://pc.qingwuit.com "点击它[演示地址]")

- 管理员后台：/Admin/login
- 账号密码：admin 123456

- 商家后台：/Seller/login
- 账号密码：18888888888 123456

- 用户后台：/user/login
- 账号密码：18888888888 123456

## H5 bate demo H5版本(测试版本) 
![H5](https://gitee.com/qingwuitcn/qwshop/raw/master/resources/js/asset/qrcode.png "H5")

## How Run 如何安装青梧商城

``` bash

composer create-project qingwuit/qwshop shop
php artisan qwshop:install

npm install
npm run prod

## linux 得给下权限 window 不用执行
chmod -R 777 ./storage/

// 在线聊天
Linux 
php artisan workerman start

win
app\Workerman\run.bat

```

## Exchange of views 意见交流 

欢迎各位提交建议、star。

**QQ群:1062159788**

**私人QQ：364825702**

**Email:bishashiwo@gmail.com**

添加请写上说明如：`青梧商城`
