
# v3测试版本
## 手动安装

+ 前往github下载源码
+ 复制根目录 `.env.example` 为 `.env`
+ 修改 `.env` 内容 域名和数据库
```shell
APP_URL=http://127.0.0.1:8000 
...
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=test
DB_USERNAME=root
DB_PASSWORD=
```

+ 在根目录执行以下命令
```shell
php artisan key:generate
php artisan passport:keys --ansi
php artisan storage:link
```
+ 导入sql文件
```shell
php artisan migrate
php artisan qwshop:mysql
```
+ 下载PHP Composer扩展 
```shell
composer install
```

+ 下载Node扩展 
```shell
npm install --registry=https://registry.npm.taobao.org --force
```
+  前端打包
```shell
npm run prod
```

+ linux 得给下权限 window 不用执行
```shell
chmod -R 777 ./storage/
```

+ Nginx 虚拟站点配置
```nginx
server {
    listen  80;

    server_name localhost;
    root  /var/www/laravel-app/public;
    index  index.html index.htm index.php;
    ...

```
### 访问
http://localhost 进入PC首页
http://localhost/Admin/login 进入PC平台后台
http://localhost/Seller/login 进入PC商户后台

## Exchange of views 意见交流 

欢迎各位提交建议、star。

**QQ群:1062159788**

**私人QQ：364825702**

**Email:bishashiwo@gmail.com**

添加请写上说明如：`青梧商城`
