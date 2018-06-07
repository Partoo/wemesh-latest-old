<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Kalnoy\Nestedset\NestedSet;

class CreateWesiteTables extends Migration
{
    /**
     * Wesite库表设计
     * categories 分类表，使用了树状结构（NestedSet包）
     * posts 文章表
     * widgets 挂件表
     * menus 菜单表，同样使用了树状结构
     */
    public function up()
    {
        Schema::create('wesite_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 20);
            NestedSet::columns($table);
            $table->timestamps();
        });

        Schema::create('wesite_posts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('category_id');
            $table->string('title', 50);
            $table->text('content');
            $table->unsignedTinyInteger('status')->default(0);
            $table->string('type')->default('post');
            $table->string('password')->nullable();
            $table->unsignedMediumInteger('order')->default(0);
            $table->unsignedMediumInteger('author_id');
            $table->json('meta')->nullable();
            $table->string('thumb')->nullable();
            $table->foreign('category_id')->references('id')->on('wesite_categories');
            $table->timestamps();

            $table->index(['author_id', 'type']);
        });

        Schema::create('wesite_widgets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 20);
            $table->string('description', 30)->default('none');
            $table->timestamps();
        });

        Schema::create('wesite_widget_post', function (Blueprint $table) {
            $table->unsignedInteger('post_id');
            $table->unsignedInteger('widget_id');

            $table->foreign('widget_id')
                ->references('id')
                ->on('wesite_widgets')
                ->onDelete('cascade');

            $table->foreign('post_id')
                ->references('id')
                ->on('wesite_posts')
                ->onDelete('cascade');

            $table->primary(['widget_id', 'post_id']);
        });

        Schema::create('wesite_menus', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 20);
            $table->string('url');
            $table->unsignedTinyInteger('order');
            $table->unsignedTinyInteger('type')->default(0)->comment('0:底部主菜单, 1:快捷菜单');
            $table->string('icon', 15);
            // NestedSet::columns($table);
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
        Schema::dropIfExists('categories');
        Schema::dropIfExists('menus');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('widgets');
    }
}
