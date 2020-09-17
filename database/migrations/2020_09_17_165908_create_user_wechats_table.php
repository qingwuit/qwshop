<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserWechatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_wechats', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->default(0)->comment('用户ID');
            $table->string('nickname',20)->comment('微信昵称');
            $table->unsignedTinyInteger('sex')->default(1)->comment('普通用户性别，1 为男性，2 为女性');
            $table->unsignedTinyInteger('headimgurl')->comment('用户头像');
            $table->string('openid',60)->comment('微信单一平台指定标识');
            $table->string('unionid',60)->comment('微信平台唯一标识');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_wechats');
    }
}
