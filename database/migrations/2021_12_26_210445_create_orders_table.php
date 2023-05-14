<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('user_id')->default(0)->comment('用户ID');
            $table->unsignedInteger('store_id')->default(0)->comment('店铺ID');
            $table->unsignedInteger('coupon_id')->default(0)->comment('优惠券日志ID');
            $table->integer('collective_id')->default(0)->comment('拼团日志ID');
            $table->string('order_no', 30)->default('')->comment('订单号');
            $table->string('order_name', 120)->default('')->comment('订单标题');
            $table->string('order_image', 150)->default('')->comment('订单图片');
            $table->unsignedDecimal('total_price', 9, 2)->default(0.00)->comment('总支付金额');
            $table->unsignedDecimal('order_price', 9, 2)->default(0.00)->comment('订单计算金额');
            $table->unsignedDecimal('order_balance', 9, 2)->default(0.00)->comment('余额支付金额');
            $table->unsignedDecimal('freight_money', 5, 2)->default(0.00)->comment('运费金额');
            $table->unsignedDecimal('coupon_money', 5, 2)->default(0.00)->comment('优惠金额');
            $table->unsignedInteger('currency_id')->default(1)->comment('货币类型');
            $table->unsignedTinyInteger('order_status')->default(1)->comment('订单支付 0 取消 1 等待支付 2等待发货 3确认收货 4等待评论 5售后 6订单完成');
            $table->unsignedTinyInteger('refund_status')->default(0)->comment('0 退款 1退货 2 处理结束');
            $table->unsignedTinyInteger('is_settlement')->default(0)->comment('是否结算');
            $table->string('delivery_no', 20)->default('')->comment('快递订单号');
            $table->string('delivery_code', 10)->default('')->comment('快递公司编码');
            $table->string('receive_name', 15)->default('')->comment('收件人名');
            $table->string('receive_tel', 11)->default('')->comment('收件人手机');
            $table->string('receive_area', 140)->default('')->comment('地址信息');
            $table->string('receive_address', 80)->default('')->comment('详细地址信息');
            $table->string('payment_name', 20)->default('')->comment('支付方式');
            $table->timestamp('pay_time')->default(now())->comment('支付时间');
            $table->timestamp('delivery_time')->default(now())->comment('发货时间');
            $table->timestamp('receipt_time')->default(now())->comment('确认收货时间');
            $table->timestamp('comment_time')->default(now())->comment('评论时间');
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
        Schema::dropIfExists('orders');
    }
}
