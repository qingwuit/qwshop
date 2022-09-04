<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('user_id')->default(0)->comment('用户ID');
            $table->string('store_name', 30)->default('神秘商户')->comment('商户名称');
            $table->string('store_logo', 150)->default('')->comment('店铺LOGO');
            $table->string('store_face_image', 150)->default('')->comment('店铺门面');
            $table->string('store_mobile', 20)->default('')->comment('店铺电话');
            $table->string('store_description', 150)->default('该商户很懒，什么也没留下')->comment('店铺描述');
            $table->string('store_slide', 550)->default('')->comment('店铺幻灯片');
            $table->string('store_mobile_slide', 550)->default('')->comment('店铺app幻灯片');
            $table->string('store_company_name', 30)->default('')->comment('公司名称');
            $table->unsignedInteger('province_id')->default(0)->comment('省ID');
            $table->unsignedInteger('city_id')->default(0)->comment('市ID');
            $table->unsignedInteger('region_id')->default(0)->comment('区ID');
            $table->string('store_lat', 20)->default('39.92')->comment('纬度');
            $table->string('store_lng', 20)->default('116.46')->comment('经度');
            $table->string('area_info', 60)->default('')->comment('地区信息');
            $table->string('store_address', 80)->default('')->comment('详细地址');
            $table->string('business_license', 150)->default('')->comment('营业执照');
            $table->string('business_license_no', 40)->default('')->comment('营业执照号码');
            $table->string('legal_person', 10)->default('')->comment('法人');
            $table->string('store_phone', 12)->default('')->comment('法人电话');
            $table->string('id_card_no', 30)->default('')->comment('身份证号码');
            $table->string('id_card_t', 150)->default('')->comment('身份证上');
            $table->string('id_card_b', 150)->default('')->comment('身份证下');
            $table->string('emergency_contact', 10)->default('')->comment('紧急联系人');
            $table->string('emergency_contact_phone', 12)->default('')->comment('紧急联系人电话');
            $table->unsignedDecimal('store_money', 9, 2)->default(0.00)->comment('账号余额');
            $table->unsignedDecimal('store_frozen_money', 9, 2)->default(0.00)->comment('账号冻结资金');
            $table->unsignedTinyInteger('store_status')->default(1)->comment('店铺状态');
            $table->unsignedTinyInteger('store_verify')->default(0)->comment('店铺审核状态');
            $table->string('store_refuse_info', 20)->default('')->comment('拒绝原因');
            $table->text('after_sale_service')->comment('售后服务');
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
        Schema::dropIfExists('stores');
    }
}
