<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaptchasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('captchas', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('username', 20)->default('')->comment('账户');
            $table->string('password', 20)->default('')->comment('密码');
            $table->string('sign', 60)->default('')->comment('签名');
            $table->unsignedTinyInteger('step')->default(0)->comment('步码');
            $table->ipAddress('ip')->default('0.0.0.0')->comment('登陆IP');
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
        Schema::dropIfExists('captchas');
    }
}
