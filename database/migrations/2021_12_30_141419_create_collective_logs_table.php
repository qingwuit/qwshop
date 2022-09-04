<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectiveLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collective_logs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('collective_id')->default(0)->comment('团ID');
            $table->unsignedInteger('user_id')->default(0)->comment('用户ID');
            $table->unsignedInteger('store_id')->default(0)->comment('店铺ID');
            $table->unsignedInteger('goods_id')->default(0)->comment('商品ID');
            $table->unsignedDecimal('discount', 6, 2)->default(0.00)->comment('折扣');
            $table->unsignedTinyInteger('need')->default(5)->comment('需要人数');
            $table->unsignedTinyInteger('status')->default(2)->comment('状态 0取消 1完成  2 拼团中');
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
        Schema::dropIfExists('collective_logs');
    }
}
