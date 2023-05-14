<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoneyLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('money_logs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('user_id')->default(0)->comment('用户');
            $table->string('name', 20)->default('未知变动')->comment('名称');
            $table->string('ename', 20)->default('money')->comment('英文名称');
            $table->decimal('money', 9, 2)->default(0.00)->comment('变动金额');
            $table->unsignedTinyInteger('is_type')->default(0)->comment('变动类型 0 余额 1冻结 2积分');
            $table->unsignedTinyInteger('is_belong')->default(0)->comment('所属用户日志 0 用户 1 商家');
            $table->unsignedInteger('currency_id')->default(1)->comment('货币类型');
            $table->text('info')->comment('原因');
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
        Schema::dropIfExists('money_logs');
    }
}
