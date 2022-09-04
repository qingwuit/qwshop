<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('belong_id')->default(0)->comment('所属用户');
            $table->unsignedInteger('pid')->default(0)->comment('上级ID');
            $table->string('name', 80)->default('')->comment('标题');
            $table->text('content')->comment('内容');
            $table->unsignedInteger('click')->default(0)->comment('点击次数');
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
        Schema::dropIfExists('articles');
    }
}
