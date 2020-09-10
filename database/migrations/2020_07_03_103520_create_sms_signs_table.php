<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsSignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_signs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',20)->default('')->comment('名称');
            $table->string('val',20)->default('')->comment('内容');
            $table->string('code',20)->default('')->comment('模版');
            $table->string('content',40)->default('')->comment('描述');
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
        Schema::dropIfExists('sms_signs');
    }
}
