<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIntegralOrderGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('integral_order_goods', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('order_id')->default(0)->comment('订单ID');
            $table->unsignedInteger('goods_id')->default(0)->comment('商品ID');
            $table->unsignedInteger('user_id')->default(0)->comment('用户ID');
            $table->string('goods_name', 120)->default('')->comment('商品名称');
            $table->string('goods_image', 150)->default('')->comment('商品图片');
            $table->unsignedInteger('buy_num')->default(1)->comment('购买数量');
            $table->unsignedDecimal('total_price', 9, 2)->default(0.00)->comment('总价格');
            $table->unsignedDecimal('goods_price', 9, 2)->default(0.00)->comment('商品单价');
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
        Schema::dropIfExists('integral_order_goods');
    }
}
