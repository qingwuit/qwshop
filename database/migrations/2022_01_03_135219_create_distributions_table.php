<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDistributionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('distributions', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('store_id')->default(0)->comment('店铺ID');
            $table->unsignedInteger('goods_id')->default(0)->comment('商品ID');
            $table->unsignedDecimal('lev_1', 6, 2)->default(0.00)->comment('一级分销');
            $table->unsignedDecimal('lev_2', 6, 2)->default(0.00)->comment('二级分销');
            $table->unsignedDecimal('lev_3', 6, 2)->default(0.00)->comment('三级分销');
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
        Schema::dropIfExists('distributions');
    }
}
