<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNoticesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notices', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('belong_id')->default(0)->comment('所属用户');
            $table->string('tag', 20)->default('')->comment('标签');
            $table->string('name', 35)->default('')->comment('标题');
            $table->text('content')->comment('内容');
            $table->unsignedTinyInteger('is_type')->default(0)->comment('类型');
            $table->unsignedTinyInteger('is_send')->default(0)->comment('发送状态');
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
        Schema::dropIfExists('notices');
    }
}
