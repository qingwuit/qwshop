<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatMsgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_msgs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->default(0)->comment('用户ID');
            $table->unsignedInteger('store_id')->default(0)->comment('店铺ID');
            $table->string('type',20)->default('text')->comment('类型');
            $table->text('content')->comment('内容');
            $table->unsignedTinyInteger('user_read')->dedfault(0)->comment('用户是否查看');
            $table->unsignedTinyInteger('store_read')->dedfault(0)->comment('店铺是否查看');
            $table->unsignedTinyInteger('send_type')->dedfault(0)->comment('发送人0用户 1商家');
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
        Schema::dropIfExists('chat_msgs');
    }
}
