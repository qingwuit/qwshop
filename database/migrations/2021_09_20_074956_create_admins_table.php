<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     * 后台管理员
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('belong_id')->default(0)->comment('所属');
            $table->string('username', 20)->default('')->comment('用户名');
            $table->string('password', 60)->default('')->comment('密码');
            $table->string('nickname', 15)->default('')->comment('昵称');
            $table->string('avatar', 150)->default('')->comment('头像');
            $table->unsignedTinyInteger('is_super')->default(0)->comment('超级管理员');
            $table->ipAddress('ip')->default('0.0.0.0')->comment('登陆IP');
            $table->timestamp('login_time')->nullable()->comment('登陆时间');
            $table->timestamp('last_login_time')->nullable()->comment('最后一次登陆');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
