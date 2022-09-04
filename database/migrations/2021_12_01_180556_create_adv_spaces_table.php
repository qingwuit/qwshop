<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvSpacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adv_spaces', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('belong_id')->default(0)->comment('所属用户');
            $table->string('name', 25)->default('')->comment('广告位名称');
            $table->unsignedInteger('width')->default(0)->comment('建议宽度');
            $table->unsignedInteger('height')->default(0)->comment('建议高度');
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
        Schema::dropIfExists('adv_spaces');
    }
}
