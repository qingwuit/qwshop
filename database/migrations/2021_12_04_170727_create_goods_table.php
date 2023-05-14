<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('brand_id')->default(0)->comment('品牌ID');
            $table->unsignedInteger('class_id')->default(0)->comment('栏目ID');
            $table->unsignedInteger('store_id')->default(0)->comment('商家ID');
            $table->string('goods_name', 120)->default('')->comment('商品名称');
            $table->string('goods_subname', 120)->default('')->comment('商品副标题');
            $table->string('goods_no', 30)->default('')->comment('商品编号');
            $table->text('goods_images')->comment('商品图片');
            $table->string('goods_master_image', 150)->default('')->comment('主图');
            $table->unsignedDecimal('goods_price', 9, 2)->default(0.00)->comment('商品价格');
            $table->unsignedDecimal('goods_market_price', 9, 2)->default(0.00)->comment('市场价格');
            $table->unsignedInteger('currency_id')->default(1)->comment('货币类型');
            $table->unsignedInteger('goods_stock')->default(0)->comment('库存');
            $table->unsignedDecimal('goods_weight', 9, 2)->default(0)->comment('商品重量');
            $table->unsignedInteger('goods_sale')->default(0)->comment('销售量');
            $table->unsignedInteger('goods_collect')->default(0)->comment('收藏量');
            $table->unsignedTinyInteger('goods_status')->default(0)->comment('上架状态');
            $table->unsignedTinyInteger('goods_verify')->default(1)->comment('审核状态');
            $table->string('refuse_info', 80)->default('暂无缘由')->comment('拒绝原因');
            $table->integer('freight_id')->default(0)->comment('运费模版 0默认');
            $table->text('goods_content')->comment('详情');
            $table->text('goods_content_mobile')->comment('手机端详情');
            $table->unsignedTinyInteger('is_recommend')->default(0)->comment('是否推荐商家首页');
            $table->unsignedTinyInteger('is_master')->default(0)->comment('是否推荐主站首页');
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
        Schema::dropIfExists('goods');
    }
}
