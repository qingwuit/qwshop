<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeckillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seckills', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('store_id')->default(0)->comment('店铺ID');
            $table->unsignedInteger('goods_id')->default(0)->comment('商品ID');
            $table->unsignedDecimal('discount', 6, 2)->default(0.00)->comment('折扣');
            $table->timestamp('start_time')->default(now())->comment('开始时间');
            $table->timestamp('end_time')->default(now()->addDays(5))->comment('结束');
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
        Schema::dropIfExists('seckills');
    }
}
