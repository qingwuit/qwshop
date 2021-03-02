<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvPositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adv_positions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ap_name',25)->default('')->comment('广告位名称');
            $table->unsignedInteger('ap_width')->default(0)->comment('建议宽度');
            $table->unsignedInteger('ap_height')->default(0)->comment('建议高度');
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
        Schema::dropIfExists('adv_positions');
    }
}
