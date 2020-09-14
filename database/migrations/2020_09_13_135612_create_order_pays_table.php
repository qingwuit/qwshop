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
            $table->increments('id');
            $table->unsignedInteger('user_id')->default(0)->comment('用户ID');
            $table->string('pay_no',30)->default('')->comment('支付号')->unique();
            $table->string('trade_no',30)->default('')->comment(' 支付平台单号');
            $table->text('order_ids')->comment('订单ID 逗号隔开');
            $table->string('payment_name',20)->default('')->comment('支付方式');
            $table->string('pay_type',10)->default('o')->comment('支付类型 o订单 r充值');
            $table->unsignedDecimal('total_price',9,2)->default(0.00)->comment('总金额');
            $table->unsignedDecimal('order_balance',9,2)->default(0.00)->comment('余额支付');
            $table->unsignedTinyInteger('pay_status')->default(0)->comment('支付状态');
            $table->timestamp('pay_time')->default(now())->comment('支付时间');
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
        Schema::dropIfExists('order_pays');
    }
}
