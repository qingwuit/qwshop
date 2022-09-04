<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cashes', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('user_id')->default(0)->comment('用户ID');
            $table->unsignedInteger('store_id')->default(0)->comment('店铺ID');
            $table->unsignedDecimal('money', 9, 2)->default(0.00)->comment('金额');
            $table->unsignedDecimal('commission', 9, 2)->default(0.00)->comment('手续费');
            $table->unsignedTinyInteger('cash_status')->default(0)->comment('状态0 提现申请 1提现成功 2拒绝提现');
            $table->text('refuse_info')->comment('拒绝原因');
            $table->string('name', 20)->default('')->comment('提现人名');
            $table->string('card_no', 50)->default('')->comment('银行卡号');
            $table->string('bank_name', 20)->default('')->comment('银行名');
            $table->string('remark', 30)->default('')->comment('备注');
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
        Schema::dropIfExists('cashes');
    }
}
