<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOauthsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oauths', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('belong_id')->default(0)->comment('用户ID');
            $table->string('model_name', 15)->default('User')->comment('模型名称');
            $table->string('oauth_name', 15)->default('User')->comment('第三方平台');
            $table->string('nickname', 20)->comment('第三方昵称');
            $table->unsignedTinyInteger('sex')->default(1)->comment('普通用户性别，1 为男性，2 为女性');
            $table->string('headimgurl', 200)->default('')->comment('用户头像');
            $table->string('openid', 60)->comment('微信单一平台指定标识');
            $table->string('unionid', 60)->comment('微信平台唯一标识');
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
        Schema::dropIfExists('oauths');
    }
}
