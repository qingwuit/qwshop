<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        DB::table('addresses')->truncate();

        DB::table('admins')->truncate();

        DB::table('admin_role')->truncate();

        DB::table('advs')->truncate();

        DB::table('adv_positions')->truncate();

        DB::table('agreements')->truncate();

        DB::table('areas')->truncate();

        DB::table('articles')->truncate();

        DB::table('configs')->truncate();


        DB::table('expresses')->truncate();


        DB::table('goods')->truncate();

        DB::table('orders')->truncate();

        DB::table('goods_brands')->truncate();

        DB::table('goods_classes')->truncate();


        DB::table('integral_goods')->truncate();


        DB::table('integral_goods_classes')->truncate();


        DB::table('menus')->truncate();


        DB::table('permissions')->truncate();
   

        DB::table('permission_groups')->truncate();


        DB::table('roles')->truncate();


        DB::table('role_menus')->truncate();


        DB::table('role_permissions')->truncate();
 

        DB::table('sms_signs')->truncate();


        DB::table('stores')->truncate();


        DB::table('store_classes')->truncate();
       
       
        DB::table('users')->truncate();
        
      

    }
}
