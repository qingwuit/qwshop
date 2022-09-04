<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIntegralOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('integral_orders', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('user_id')->default(0)->comment('用户ID');
            $table->string('order_no', 30)->default('')->comment('订单号');
            $table->string('order_name', 120)->default('')->comment('订单标题');
            $table->string('order_image', 150)->default('')->comment('订单图片');
            $table->unsignedDecimal('total_price', 9, 2)->default(0.00)->comment('总支付金额');
            $table->unsignedDecimal('order_price', 9, 2)->default(0.00)->comment('订单计算金额');
            $table->unsignedTinyInteger('order_status')->default(1)->comment('订单支付 0 取消 1 等待支付 2订单完成');
            $table->string('delivery_no', 20)->default('')->comment('快递订单号');
            $table->string('delivery_code', 10)->default('')->comment('快递公司编码');
            $table->string('receive_name', 15)->default('')->comment('收件人名');
            $table->string('receive_tel', 11)->default('')->comment('收件人手机');
            $table->string('receive_area', 140)->default('')->comment('地址信息');
            $table->string('receive_address', 80)->default('')->comment('详细地址信息');
            $table->timestamp('pay_time')->default(now())->comment('支付时间');
            $table->timestamp('delivery_time')->default(now())->comment('发货时间');
            $table->string('remark', 150)->default('')->comment('备注');
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
        Schema::dropIfExists('integral_orders');
    }
}
