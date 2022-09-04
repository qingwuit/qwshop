<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('user_id')->default(0)->comment('用户ID');
            $table->string('receive_name', 15)->default('')->comment('收件人名');
            $table->string('receive_tel', 11)->default('')->comment('收件人手机');
            $table->string('area_info', 140)->default('')->comment('地址信息');
            $table->string('address', 80)->default('')->comment('详细地址信息');
            $table->unsignedInteger('province_id')->default(0)->comment('省份');
            $table->unsignedInteger('city_id')->default(0)->comment('城市');
            $table->unsignedInteger('region_id')->default(0)->comment('区县');
            $table->unsignedTinyInteger('is_default')->default(0)->comment('是否默认');
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
        Schema::dropIfExists('addresses');
    }
}
