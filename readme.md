## Welcome to Qingwu mall 欢迎使用 青梧商城系统 (B2B2C)
![logo](http://pc.qingwuit.com/dist/images/logo.png "logo")

> 我们理解您需要一套前后端分离，功能齐全操作简易的商城框架 青梧商城系统 就是秉承着这样的目的开发出来的，或许您是开发人员，又或许您是即将要创业的老板，都可以基于青梧商城系统进行快速项目启动。

## Need environment and existing functions 需要环境和已有功能
> Laravel 7.x + Vue 前后分离

> 多商户、秒杀、团购、优惠券、在线聊天、三级分销、积分商城、Wechat支付、Alipay支付

> 支持二次开发

> PHP >= 7.3

### 20201231 【失踪人士回归】为什么没维护了，因为要上班。那为什么又更新了，因为我把老板又炒了。(这次更新主要是将方法集成到Service层，然后前端框架用的antdv,这个前端框架很坑，我不会用。)

## Code address 代码地址
[点击它[代码地址] click it](https://gitee.com/qingwuitcn/qwShopPhp "点击它[代码地址]")

## Demo address 演示地址
[点击它[演示地址] click it](http://pc.qingwuit.com "点击它[演示地址]")
> 商家后台：/Seller/login
> 账号密码：18888888888 123456

> 管理员后台：/Admin/login
> 账号密码：admin 123456

> 用户后台：/user/login
> 账号密码：18888888888 123456

## How Run 如何安装青梧商城

``` php

composer create-project qingwuit/qwshop blog
php artisan qwshop:install

npm install
npm prod

// 在线聊天
Ngnix 
php artisan workerman start

win
app\Workerman\run.bat

```

## Exchange of views 意见交流 
欢迎各位提交建议。**联系方式 私人QQ：364825702 QQ群:1062159788** 添加请写上说明如：`青梧商城`

## Precautions for use 注意事项

1、需要自行配置PHP环境、和node 环境(安装扩展尽量使用国内镜像)

2、npm run 打包过程失败，请查看`node-sass` 是否有下载成功，尝试重新下载或者去淘宝镜像下载 确定`node版本是否过低`。

3、安装后无法打开，是否忘记配置伪静态ngnix  linux 查看 `chmod -R 777 storage` 是否给权限

4、演示得到链接为1.0版本，如需看最新版本得自己本地安装

4、`暂时没有APP 小程序版本，还在开发`，如果有做完我会第一时间发出来的

## Common Problem 常见问题

## Version 更新记录

#### 20210104
1、mysql5.5 不兼容的问题

2、sql文件不全的问题

3、安装命令写错了