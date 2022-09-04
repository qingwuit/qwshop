<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderSettlementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_settlements', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('order_id')->default(0)->comment('订单ID');
            $table->unsignedInteger('user_id')->default(0)->comment('用户ID');
            $table->unsignedInteger('store_id')->default(0)->comment('店铺ID');
            $table->string('settlement_no', 30)->default('')->comment('结算单号');
            $table->unsignedDecimal('total_price', 9, 2)->default(0.00)->comment('订单金额');
            $table->unsignedDecimal('settlement_price', 9, 2)->default(0.00)->comment('结算金额');
            $table->unsignedTinyInteger('status')->default(0)->comment('结算状态');
            $table->text('info')->comment('备注');
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
        Schema::dropIfExists('order_settlements');
    }
}
