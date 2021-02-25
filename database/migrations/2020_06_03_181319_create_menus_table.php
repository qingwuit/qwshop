<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pid')->default(0)->comment('父ID');
            $table->string('name',20)->default('')->comment('菜单名称');
            $table->string('ename',20)->default('')->comment('菜单英文');
            $table->string('icon',20)->default('')->comment('图标');
            $table->string('link',60)->default('')->comment('链接');
            $table->unsignedTinyInteger('is_type')->default(0)->comment('类型0 后台 1商家');
            $table->unsignedTinyInteger('is_sort')->default(0)->comment('排序');
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
        Schema::dropIfExists('menus');
    }
}
