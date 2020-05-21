# 欢迎使用 青梧商城系统

------

我们理解您需要一套前后端分离，功能齐全操作简易的商城框架 **青梧商城系统** 就是秉承着这样的目的开发出来的，或许您是开发人员，又或许您是即将要创业的老板，都可以基于青梧商城系统进行快速项目启动。（不建议商用，代码质量有点差，然后bug很多没改好）


> * Laravel 6.x + Vue 前后分离
> * 多商户、秒杀、团购、在线聊天、三级分销（未写完）、Wechat支付、Alipay支付
> * 支持二次开发
> * PHP >= 7.2

  
  
  

### 2020-05-18 代码整合Bug修复部分（之前分开的不影响，依旧可以将这个php项目当成api项目）

    1. 这次修改主要是吧原本分离出去的vue部分用larave-mix 放进php项目了，不再需要分别搭建
    2. 然后修复一些vue路由的Bug 相同路由params值改变无法跳转的问题
    3. 商品加入购物车导致库存会减少已经注释掉了
    4. 积分商城栏目下积分产品无法显示Bug


  
  

![cmd-markdown-logo](http://pc.qingwuit.com/pc/logo.png)
  



## 代码地址：

[Laravel 后端代码地址](https://gitee.com/qingwuitcn/qwShopPhp)
  
  
  
~~[Vue 前端代码地址(已经和当前Laravel项目合并，不再需要两边处理)](https://gitee.com/qingwuitcn/qwShopVue)~~
  



## 演示地址:
[演示地址](http://pc.qingwuit.com)

> * [后台地址](http://pc.qingwuit.com/Admin/login): admin 123456 
> * [商家地址](http://pc.qingwuit.com/Seller/login): 18888888888 123456 
> * [用户登录](http://pc.qingwuit.com/user/login): 18888888888 123456 (数据库每日会重新导入数据信息)

  

> 欢迎各位提交建议、BUG。联系方式 **QQ:364825702**  `添加请写上说明如：青梧商城`。
  


------
  



## 如何使用青梧商城？



1. Laravel

```php
composer install (安装扩展) // 根目录运行，如没有composer请前往搜索引擎学习安装

cp ./.env.example ./.env (WINDOWS 直接复制即可)

php artisan key:generate

chmod -R 777 ./storage/ （WINDOWS 不用执行）

source xx.sql  (导入数据库) 

vim ./env  (修改配置 数据库 邮件 redis 等等)  // win 系统直接编辑器修改就行

php artisan jwt:secret （这条命令会在 .env 文件下生成一个加密密钥，如：JWT_SECRET=foobar）

npm run install (安装扩展) // 也是在本项目根目录 没有node环境自行安装

前端记得要修改\resources\js\plugins\api.js  应该是第一行 后端api 根域名，应该是127.0.0.1:8000 （这个是你项目域名）

npm run production (压缩打包) // 在本项目根目录执行

```

2. 如何开启在线聊天

```php

php artisan workerman start -d  (WINDOWS则不需要运行左侧命令 在根目录app/Workerman/run.bat  点击运行即可)

前端记得去resources\js\components\chat 下修改相对应的wss 链接才行

```

3. 定时任务

```php

cd /www/wwwroot/api.qingwuit.com && php artisan schedule:run >> /dev/null 2>&1  （WINDOWS php artisan schedule:run） 

```

## 数据库文件 （是PHP代码根目录下的admin.sql）

------

## 常见问题

1. 刷新404 后台无法访问。（20200518 后克隆代码没有这个问题了）
https://router.vuejs.org/zh/guide/essentials/history-mode.html   可根据自己环境修改

2. 地址缺少
[代码下载](https://gitee.com/qingwuitcn/address_collection_php)   可以自己去国家统计采集或者用我这个代码
  


## 代码修改

#### 2020-05-18 代码整合Bug修复部分（之前分开的不影响，依旧可以将这个php项目当成api项目）

1. 这次修改主要是吧原本分离出去的vue部分用larave-mix 放进php项目了，不再需要分别搭建
2. 然后修复一些vue路由的Bug 相同路由params值改变无法跳转的问题
3. 商品加入购物车导致库存会减少已经注释掉了
4. 积分商城栏目下积分产品无法显示Bug

