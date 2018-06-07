<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateArchivesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archives', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('patient_id')->unsigned()->nullable();
            $table->integer('pub_id')->unsigned(); // 公卫体检表识别号，改号可能全局不唯一，但对于患者唯一
            $table->date('checkdate', 12)->nullable(); // 查体日期
            $table->string('doctor', 10)->nullable(); // 查体医生姓名
            $table->string('village')->nullable(); // 村
            $table->json('result')->nullable(); // 查体结果
            $table->json('abnormal')->nullable(); // 查体异常
            $table->timestamps();

            $table->index(['village', 'checkdate']);
            $table->foreign('patient_id')
                ->references('id')
                ->on('patients')
                ->onDelete('cascade')
                ->onUpdate('cascade');
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
        Schema::dropIfExists('archives');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
