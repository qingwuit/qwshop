<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppVersionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_versions', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name', 30)->default('名称')->comment('标题');
            $table->string('version', 20)->default('1.0.0')->comment('版本名称');
            $table->unsignedInteger('version_code')->default(100)->comment('版本号');
            $table->string('url', 150)->default('')->comment('上传地址');
            $table->string('device', 10)->default('android')->comment('设备类型');
            $table->string('apple_id', 20)->default('')->comment('苹果ID');
            $table->unsignedTinyInteger('is_wgt')->default(0)->comment('是否资源包');
            $table->text('content')->comment('更新内容');
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
        Schema::dropIfExists('app_versions');
    }
}
