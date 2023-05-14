<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderPaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_pays', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('belong_id')->default(0)->comment('所属用户');
            $table->string('name', 150)->default('')->comment('订单名称');
            $table->string('order_ids', 200)->default('')->comment('订单IDs');
            $table->string('pay_no', 32)->default('')->comment('平台支付号');
            $table->string('trade_no', 32)->default('')->comment('第三方订单号');
            $table->string('payment_name', 20)->default('')->comment('支付平台');
            $table->string('device', 20)->default('')->comment('设备');
            $table->unsignedTinyInteger('is_recharge')->default(0)->comment('充值');
            $table->unsignedDecimal('total', 9, 2)->default(0.00)->comment('金额');
            $table->unsignedDecimal('balance', 9, 2)->default(0.00)->comment('余额');
            $table->unsignedInteger('currency_id')->default(1)->comment('货币类型');
            $table->unsignedTinyInteger('pay_status')->default(0)->comment('支付状态');
            $table->timestamp('pay_time')->comment('支付时间');
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
        Schema::dropIfExists('order_pays');
    }
}
