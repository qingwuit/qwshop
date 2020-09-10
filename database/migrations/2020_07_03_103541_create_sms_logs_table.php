<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('phone',12)->default('')->comment('手机号码');
            $table->string('name',30)->default('')->comment('短信类型名称');
            $table->string('content',10)->default('')->comment('短信内容');
            $table->unsignedTinyInteger('status')->default(0)->comment('发送状态');
            $table->ipAddress('ip')->default('0.0.0.0')->comment('发送IP');
            $table->string('error_msg',50)->default('')->comment('错误信息');
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
        Schema::dropIfExists('sms_logs');
    }
}
