<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_menus', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('pid')->default(0)->comment('上级ID');
            $table->string('name', 35)->default('')->comment('菜单名称');
            $table->string('ename', 35)->default('')->comment('英文名称');
            $table->string('icon', 20)->default('')->comment('图标');
            $table->string('apis', 80)->default('')->comment('菜单路由');
            $table->string('view', 40)->default('')->comment('前端视图');
            $table->unsignedTinyInteger('is_open')->default(0)->comment('外链跳转');
            $table->string('content', 40)->default('')->comment('菜单描述');
            $table->unsignedInteger('is_sort')->default(0)->comment('排序');
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
        Schema::dropIfExists('user_menus');
    }
}
