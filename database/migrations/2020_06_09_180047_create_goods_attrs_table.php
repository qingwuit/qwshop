<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoodsAttrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_attrs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('goods_id')->default(0)->comment('商品ID');
            $table->string('name',20)->default('')->comment('属性名称');
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
        Schema::dropIfExists('goods_attrs');
    }
}
