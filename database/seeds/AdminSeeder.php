<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->truncate();
        DB::table('admins')->insert([
            'username' => 'admin',
            'nickname' => '管理员',
            'password' => Hash::make('123456'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
