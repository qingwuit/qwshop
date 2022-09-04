<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refunds', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('user_id')->default(0)->comment('用户ID');
            $table->unsignedInteger('store_id')->default(0)->comment('店铺ID');
            $table->unsignedInteger('order_id')->default(0)->comment('订单ID');
            $table->unsignedTinyInteger('refund_type')->default(0)->comment('售后类型 0 退款 1退货');
            $table->unsignedTinyInteger('refund_verify')->default(0)->comment('售后状态 0 处理中 1同意 2拒绝');
            $table->unsignedTinyInteger('refund_step')->default(0)->comment('售后步骤  0等待用户填写单号 1等待商家 2商家确定收货并发货 3用户确定收货订单成功');
            $table->string('delivery_no', 20)->default('')->comment('快递订单号');
            $table->string('delivery_code', 10)->default('')->comment('快递公司编码');
            $table->string('re_delivery_no', 20)->default('')->comment('商家快递订单号');
            $table->string('re_delivery_code', 10)->default('')->comment('商家快递公司编码');
            $table->text('refund_remark')->comment('售后原因');
            $table->text('images')->comment('图片');
            $table->text('refuse_remark')->comment('拒绝原因');
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
        Schema::dropIfExists('refunds');
    }
}
