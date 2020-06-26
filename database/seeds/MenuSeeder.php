<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('menus')->truncate();
        DB::table('menus')->insert([
            // 用户中心
            [
                'id'            => 1,
                'pid'           => 0,
                'is_type'       => 0,
                'name'          => '用户中心',
                'icon'          => 'iconchengyuan',
                'link'          => '#',
                'is_sort'       => '0',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
                // 用户管理
                [
                    'id'            => 2,
                    'pid'           => 1,
                    'is_type'       => 0,
                    'name'          => '用户管理',
                    'icon'          => '',
                    'link'          => '#',
                    'is_sort'       => '0',
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ],
                    // 管理员
                    [
                        'id'            => 3,
                        'pid'           => 2,
                        'is_type'       => 0,
                        'name'          => '管理员',
                        'icon'          => '',
                        'link'          => '#',
                        'is_sort'       => '0',
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ],
                    // 平台用户
                    [
                        'id'            => 4,
                        'pid'           => 2,
                        'is_type'       => 0,
                        'name'          => '平台用户',
                        'icon'          => '',
                        'link'          => '#',
                        'is_sort'       => '0',
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ],

                // 菜单管理
                [
                    'id'            => 5,
                    'pid'           => 1,
                    'is_type'       => 0,
                    'name'          => '菜单管理',
                    'icon'          => '',
                    'link'          => '#',
                    'is_sort'       => '0',
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ],
                    // 后台菜单
                    [
                        'id'            => 6,
                        'pid'           => 5,
                        'is_type'       => 0,
                        'name'          => '后台菜单',
                        'icon'          => '',
                        'link'          => '#',
                        'is_sort'       => '0',
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ],
                    // 商家菜单
                    [
                        'id'            => 7,
                        'pid'           => 5,
                        'is_type'       => 0,
                        'name'          => '商家菜单',
                        'icon'          => '',
                        'link'          => '#',
                        'is_sort'       => '0',
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ],

            //     // 用户管理
            //     [
            //         'id'            => 10,
            //         'pid'           => 1,
            //         'is_type'       => 0,
            //         'name'          => '用户管理',
            //         'icon'          => '',
            //         'link'          => '#',
            //         'is_sort'       => '0',
            //         'created_at'    => now(),
            //         'updated_at'    => now(),
            //     ],
            //     // 菜单管理
            //     [
            //         'id'            => 11,
            //         'pid'           => 1,
            //         'is_type'       => 0,
            //         'name'          => '菜单管理',
            //         'icon'          => '',
            //         'link'          => '#',
            //         'is_sort'       => '1',
            //         'created_at'    => now(),
            //         'updated_at'    => now(),
            //     ],
            //     // 角色管理
            //     [
            //         'id'            => 12,
            //         'pid'           => 1,
            //         'is_type'       => 0,
            //         'name'          => '角色管理',
            //         'icon'          => '',
            //         'link'          => '#',
            //         'is_sort'       => '2',
            //         'created_at'    => now(),
            //         'updated_at'    => now(),
            //     ],
            //     // 接口管理
            //     [
            //         'id'            => 13,
            //         'pid'           => 1,
            //         'is_type'       => 0,
            //         'name'          => '接口管理',
            //         'icon'          => '',
            //         'link'          => '#',
            //         'is_sort'       => '3',
            //         'created_at'    => now(),
            //         'updated_at'    => now(),
            //     ],
            //         // 接口分组
            //         [
            //             'id'            => 14,
            //             'pid'           => 13,
            //             'is_type'       => 0,
            //             'name'          => '接口分组',
            //             'icon'          => '',
            //             'link'          => '#',
            //             'is_sort'       => '0',
            //             'created_at'    => now(),
            //             'updated_at'    => now(),
            //         ],
            //         // 接口列表
            //         [
            //             'id'            => 15,
            //             'pid'           => 13,
            //             'is_type'       => 0,
            //             'name'          => '接口列表',
            //             'icon'          => '',
            //             'link'          => '#',
            //             'is_sort'       => '1',
            //             'created_at'    => now(),
            //             'updated_at'    => now(),
            //         ],

            // // 配置中心
            // [
            //     'id'            => 2,
            //     'pid'           => 0,
            //     'is_type'       => 0,
            //     'name'          => '配置中心',
            //     'icon'          => 'iconshezhi',
            //     'link'          => '#',
            //     'is_sort'       => '1',
            //     'created_at'    => now(),
            //     'updated_at'    => now(),
            // ],

            //     // 网站配置
            //     [
            //         'id'            => 16,
            //         'pid'           => 2,
            //         'is_type'       => 0,
            //         'name'          => '网站配置',
            //         'icon'          => '',
            //         'link'          => '#',
            //         'is_sort'       => '0',
            //         'created_at'    => now(),
            //         'updated_at'    => now(),
            //     ],
            //     // 支付配置
            //     [
            //         'id'            => 17,
            //         'pid'           => 2,
            //         'is_type'       => 0,
            //         'name'          => '支付配置',
            //         'icon'          => '',
            //         'link'          => '#',
            //         'is_sort'       => '1',
            //         'created_at'    => now(),
            //         'updated_at'    => now(),
            //     ],
            //     // 上传配置
            //     [
            //         'id'            => 18,
            //         'pid'           => 2,
            //         'is_type'       => 0,
            //         'name'          => '上传配置',
            //         'icon'          => '',
            //         'link'          => '#',
            //         'is_sort'       => '2',
            //         'created_at'    => now(),
            //         'updated_at'    => now(),
            //     ],
            //     // 短信配置
            //     [
            //         'id'            => 19,
            //         'pid'           => 2,
            //         'is_type'       => 0,
            //         'name'          => '短信配置',
            //         'icon'          => '',
            //         'link'          => '#',
            //         'is_sort'       => '3',
            //         'created_at'    => now(),
            //         'updated_at'    => now(),
            //     ],
            //     // Oauth配置
            //     [
            //         'id'            => 20,
            //         'pid'           => 2,
            //         'is_type'       => 0,
            //         'name'          => 'Oauth配置',
            //         'icon'          => '',
            //         'link'          => '#',
            //         'is_sort'       => '4',
            //         'created_at'    => now(),
            //         'updated_at'    => now(),
            //     ],

            // // 商品中心
            // [
            //     'id'            => 3,
            //     'pid'           => 0,
            //     'is_type'       => 0,
            //     'name'          => '商品中心',
            //     'icon'          => 'iconchanpin1',
            //     'link'          => '#',
            //     'is_sort'       => '2',
            //     'created_at'    => now(),
            //     'updated_at'    => now(),
            // ],

            // // 店铺中心
            // [
            //     'id'            => 4,
            //     'pid'           => 0,
            //     'is_type'       => 0,
            //     'name'          => '店铺中心',
            //     'icon'          => 'icondianpu',
            //     'link'          => '#',
            //     'is_sort'       => '3',
            //     'created_at'    => now(),
            //     'updated_at'    => now(),
            // ],

            // // 订单中心
            // [
            //     'id'            => 5,
            //     'pid'           => 0,
            //     'is_type'       => 0,
            //     'name'          => '订单中心',
            //     'icon'          => 'icondingdan',
            //     'link'          => '#',
            //     'is_sort'       => '4',
            //     'created_at'    => now(),
            //     'updated_at'    => now(),
            // ],

            // // 积分商城
            // [
            //     'id'            => 6,
            //     'pid'           => 0,
            //     'is_type'       => 0,
            //     'name'          => '积分商城',
            //     'icon'          => 'iconjifen',
            //     'link'          => '#',
            //     'is_sort'       => '5',
            //     'created_at'    => now(),
            //     'updated_at'    => now(),
            // ],

            // // 财务资金
            // [
            //     'id'            => 7,
            //     'pid'           => 0,
            //     'is_type'       => 0,
            //     'name'          => '财务资金',
            //     'icon'          => 'iconcaiwu',
            //     'link'          => '#',
            //     'is_sort'       => '6',
            //     'created_at'    => now(),
            //     'updated_at'    => now(),
            // ],

            // // 物流中心
            // [
            //     'id'            => 8,
            //     'pid'           => 0,
            //     'is_type'       => 0,
            //     'name'          => '物流中心',
            //     'icon'          => 'iconwuliu',
            //     'link'          => '#',
            //     'is_sort'       => '7',
            //     'created_at'    => now(),
            //     'updated_at'    => now(),
            // ],

            // // 营销活动
            // [
            //     'id'            => 9,
            //     'pid'           => 0,
            //     'is_type'       => 0,
            //     'name'          => '营销活动',
            //     'icon'          => 'iconVIP1',
            //     'link'          => '#',
            //     'is_sort'       => '8',
            //     'created_at'    => now(),
            //     'updated_at'    => now(),
            // ],

            // // 数据统计
            // [
            //     'id'            => 10,
            //     'pid'           => 0,
            //     'is_type'       => 0,
            //     'name'          => '数据统计',
            //     'icon'          => 'iconstatistics',
            //     'link'          => '#',
            //     'is_sort'       => '8',
            //     'created_at'    => now(),
            //     'updated_at'    => now(),
            // ],
       
        ]);
    }
}
