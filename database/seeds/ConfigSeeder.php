<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('configs')->truncate();
        DB::table('configs')->insert([
            [
                'name'          =>  'web_name', 
                'val'           =>  '青梧商城',
                'content'       =>  '网站名',
            ],
            [
                'name'          =>  'title', 
                'val'           =>  '青梧商城-B2B2C',
                'content'       =>  '网站标题',
            ],
            [
                'name'          =>  'keywords', 
                'val'           =>  '青梧商城,B2B2C,laravel,vue',
                'content'       =>  '关键字',
            ],
            [
                'name'          =>  'description', 
                'val'           =>  '青梧商城,电商开发平台',
                'content'       =>  '网站描述',
            ],
            [
                'name'          =>  'logo', 
                'val'           =>  '',
                'content'       =>  '网站Logo',
            ],
            [
                'name'          =>  'icon', 
                'val'           =>  '',
                'content'       =>  '游览器Logo',
            ],
            [
                'name'          =>  'mobile', 
                'val'           =>  '',
                'content'       =>  '联系电话',
            ],
            [
                'name'          =>  'email', 
                'val'           =>  'bishashiwo@gmail.com',
                'content'       =>  '邮箱',
            ],
            [
                'name'          =>  'icp', 
                'val'           =>  '',
                'content'       =>  '备案信息',
            ],
            [
                'name'          =>  'web_status', 
                'val'           =>  '1',
                'content'       =>  '网站状态 0关 1开',
            ],
            [
                'name'          =>  'web_close_info', 
                'val'           =>  '网站维护中...',
                'content'       =>  '网站关闭原因',
            ],
            [
                'name'          =>  'ali_pay', 
                'val'           =>  '',
                'content'       =>  '阿里云支付配置',
            ],
            [
                'name'          =>  'alioss', 
                'val'           =>  '{"status":false,"access_id":"LTAI4Fyqjex9noDp9uHU1E66","access_key":"i5Y8PAumsXdjdw4NvjzPlbRmTwssT2","bucket":"qwshop","endpoint":"oss-cn-qingdao.aliyuncs.com","cdnDomain":"","ssl":false,"isCName":false}',
                'content'       =>  '阿里云OSS',
            ],
            [
                'name'          =>  'alisms', 
                'val'           =>  '',
                'content'       =>  '阿里云sms',
            ],
            [
                'name'          =>  'amap_ak', 
                'val'           =>  '',
                'content'       =>  '高德地图密钥',
            ],
            [
                'name'          =>  'wechat_public', 
                'val'           =>  '',
                'content'       =>  '微信公众号配置',
            ],
            [
                'name'          =>  'wechat_pay', 
                'val'           =>  '',
                'content'       =>  '微信支付配置',
            ],
            [
                'name'          =>  'paypal', 
                'val'           =>  '',
                'content'       =>  'paypal支付配置',
            ],
            [
                'name'          =>  'oauth', 
                'val'           =>  '',
                'content'       =>  '第三方登陆配置',
            ],
            [
                'name'          =>  'goods_verify', 
                'val'           =>  '0',
                'content'       =>  '商品审核',
            ],
            [
                'name'          =>  'cash_rate', 
                'val'           =>  '0',
                'content'       =>  '提现手续费',
            ],
        ]);
    }
}
