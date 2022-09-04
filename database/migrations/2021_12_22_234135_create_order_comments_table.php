<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_comments', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('user_id')->default(0)->comment('用户ID');
            $table->unsignedInteger('order_id')->default(0)->comment('订单ID');
            $table->unsignedInteger('goods_id')->default(0)->comment('商品ID');
            $table->unsignedInteger('store_id')->default(0)->comment('店铺ID');
            $table->unsignedDecimal('score', 5, 2)->default(5.00)->comment('综合评分');
            $table->unsignedDecimal('agree', 5, 2)->default(5.00)->comment('描述相符');
            $table->unsignedDecimal('service', 5, 2)->default(5.00)->comment('服务态度');
            $table->unsignedDecimal('speed', 5, 2)->default(5.00)->comment('发货速度');
            $table->text('reply')->comment('回复内容');
            $table->timestamp('reply_time')->default(now())->comment('回复时间');
            $table->text('content')->comment('内容');
            $table->text('image')->comment('图片逗号隔开');
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
        Schema::dropIfExists('order_comments');
    }
}
