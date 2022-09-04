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
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('phone', 15)->default('')->comment('手机号');
            $table->string('name', 15)->default('')->comment('类型');
            $table->string('content', 15)->default('')->comment('发送内容');
            $table->unsignedTinyInteger('status')->default(0)->comment('发送状态');
            $table->text('error_msg', 20)->comment('错误信息');
            $table->ipAddress('ip')->default('0.0.0.0')->comment('操作IP');
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
        Schema::dropIfExists('sms_logs');
    }
}
