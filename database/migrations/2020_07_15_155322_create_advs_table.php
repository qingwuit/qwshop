<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateAdvsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ap_id')->default(0)->comment('广告位ID');
            $table->string('name',20)->default('')->comment('广告名称');
            $table->string('url',100)->default('#')->comment('链接');
            $table->string('image_url',150)->default('')->comment('图片地址');
            $table->timestamp('adv_start')->comment('开始时间');
            $table->timestamp('adv_end')->comment('结束时间');
            $table->unsignedInteger('adv_sort')->default(0)->comment('排序');
            $table->unsignedInteger('adv_type')->default(0)->comment('类型');
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
        Schema::dropIfExists('advs');
    }
}
