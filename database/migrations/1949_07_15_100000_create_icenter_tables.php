<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Kalnoy\Nestedset\NestedSet;
use Stario\Icenter\Services\Icenter;

class CreateIcenterTables extends Migration
{
    /**
     * icenter表结构，涵盖了基础功能
     * 日后扩展可以用modules添加
     * 1. admins 用户表
     * 2. profiles 用户详细资料表
     * 3. units 部门表
     * @return void
     */
    public function up()
    {

        Schema::create('units', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('owner_id')->unsigned()->index()->default(1);
            $table->string('name', 60);
            $table->timestamps();
        });

        Schema::create('admins', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 12)->default('none');
            $table->string('mobile', 12);
            $table->string('email', 30)->default('none');
            $table->string('wx_token')->comment('wechat Token')->default('none');
            $table->string('password');
            $table->tinyInteger('status')->default(1);
            $table->rememberToken();
            $table->integer('unit_id')->unsigned()->default(1);
            $table->timestamp('last_login')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('last_ip', 45)->default('none');
            // $table->json('meta')->comment('备用')->nullable();
            $table->timestamps();

            $table->foreign('unit_id')
            ->references('id')
            ->on('units')
            ->onUpdate('cascade')
            ->onDelete('cascade');
        });

        Schema::create('profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nickname', 12)->default('none');
            $table->integer('admin_id');
            $table->string('avatar', 100);
            $table->string('sex', 5)->default('女');
            $table->string('qq', 15)->default('none');
            $table->string('birthplace', 30)->default('none');
            $table->date('birthday')->default('1977-7-15');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('menus', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 30);
            $table->string('icon', 30)->default('none');
            $table->string('path');
            $table->unsignedTinyInteger('order');
            NestedSet::columns($table);
            $table->timestamps();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->unsignedTinyInteger('id')->unique();
            $table->string('site_name', 30)->default('WeMesh');
            // $table->json('meta')->comment('扩展备用')->nullable();
            $table->timestamps();
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 30);
            $table->string('description', 30)->default('none');
            $table->string('guard_name', 20);
            $table->timestamps();
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 30);
            $table->string('label', 30)->default('none');
            $table->string('guard_name', 20);
            $table->timestamps();
        });

        Schema::create('menu_permission', function (Blueprint $table) {
            $table->integer('permission_id')->unsigned();
            $table->integer('menu_id')->unsigned();

            $table->foreign('permission_id')
                ->references('id')
                ->on('permissions')
                ->onDelete('cascade');

            $table->foreign('menu_id')
                ->references('id')
                ->on('menus')
                ->onDelete('cascade');

            $table->primary(['permission_id', 'menu_id']);
        });

        Schema::create('model_has_permissions', function (Blueprint $table) {
            $table->integer('permission_id')->unsigned();
            $table->morphs('model');

            $table->foreign('permission_id')
                ->references('id')
                ->on('permissions')
                ->onDelete('cascade');

            $table->primary(['permission_id', 'model_id', 'model_type']);
        });

        Schema::create('model_has_roles', function (Blueprint $table) {
            $table->integer('role_id')->unsigned();
            $table->morphs('model');

            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');

            $table->primary(['role_id', 'model_id', 'model_type']);
        });

        Schema::create('role_has_permissions', function (Blueprint $table) {
            $table->integer('permission_id')->unsigned();
            $table->integer('role_id')->unsigned();

            $table->foreign('permission_id')
                ->references('id')
                ->on('permissions')
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');

            $table->primary(['permission_id', 'role_id']);
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
        Schema::dropIfExists('profiles');
        Schema::dropIfExists('units');
        Schema::dropIfExists('admins');
        Schema::dropIfExists('modules');
        Schema::dropIfExists('settings');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
