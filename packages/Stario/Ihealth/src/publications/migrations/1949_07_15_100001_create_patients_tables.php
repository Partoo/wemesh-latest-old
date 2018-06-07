<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePatientsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('openid', 66)->unique()->nullable();
            $table->string('name', 10); // 姓名
            $table->tinyInteger('gender')->unsigned()->default(0); // 性别
            $table->string('identify', 20)->unique()->nullable(); //身份证
            $table->date('birthday', 12)->nullable();
            $table->string('phone', 12)->nullable(); // 电话
            $table->string('village')->nullable(); // 村
            $table->string('fn', 26)->nullable(); // 公卫编号
            $table->unsignedTinyInteger('type')->default(0); // 老年人、新生儿、孕妇...
            $table->string('paytype', 20)->nullable();
            $table->boolean('livetype')->default(true); // 真为常驻 否则为流动
            $table->string('nation', 15)->nullable(); // 民族
            $table->timestamps();

            $table->index(['birthday', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('patients');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
