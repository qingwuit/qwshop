# 欢迎使用 青梧商城系统

------

我们理解您需要一套前后端分离，功能齐全操作简易的商城框架 **青梧商城系统** 就是秉承着这样的目的开发出来的，或许您是开发人员，又或许您是即将要创业的老板，都可以基于青梧商城系统进行快速项目启动。


> * Laravel 6.x + Vue 前后分离
> * 多商户、秒杀、团购、在线聊天、三级分销、Wechat支付、Alipay支付
> * 支持二次开发

![cmd-markdown-logo](http://pc.qingwuit.com/pc/logo.png)

## 代码地址：

[Laravel 后端代码地址](https://gitee.com/qingwuitcn/qwShopPhp)
[Vue 前端代码地址](https://gitee.com/qingwuitcn/qwShopVue)

## 演示地址:
[演示地址](http://pc.qingwuit.com)

> * [后台地址](http://pc.qingwuit.com/Admin/login): admin 123456 
> * [商家地址](http://pc.qingwuit.com/Seller/login): 18888888888 123456 
> * [用户登录](http://pc.qingwuit.com/user/login): 18888888888 123456 (数据库每日会重新导入数据信息)


> 欢迎各位提交建议、BUG。联系方式 **QQ:364825702**  `添加请写上说明如：青梧商城`。

------

## 如何使用青梧商城？



1. Laravel linux

```php
composer install (安装扩展)

cp ./.env.example ./.env (WINDOS 直接复制即可)

php artisan key:generate

chmod -R 777 ./storage/

source xx.sql  (导入数据库)

vim ./env  (修改配置 数据库 邮件 redis 等等)

php artisan jwt:secret （这条命令会在 .env 文件下生成一个加密密钥，如：JWT_SECRET=foobar）

```

2. Vue

```vue
npm install (安装扩展)

前端记得要修改src\plugins\api.js  应该是第一行 后端api 根域名，应该是127.0.0.1:8000 （这个是你api服务器域名）

npm run build
```


## 数据库文件 （是PHP代码根目录下的admin.sql）

------

## 常见问题

1. 刷新404 后台无法访问。
https://router.vuejs.org/zh/guide/essentials/history-mode.html   可根据自己环境修改

