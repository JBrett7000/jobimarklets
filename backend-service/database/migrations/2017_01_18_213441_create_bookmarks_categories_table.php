<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookmarksCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookmarks_categories', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('bookmark_id')->unsigned();
            $table->integer('category_id')->unsigned();
        });


        Schema::table('bookmarks_categories', function (Blueprint $table) {
           $table->foreign('user_id')
               ->references('id')
               ->on('users')
               ->onDelete('cascade');

           $table->foreign('bookmark_id')
               ->references('id')
               ->on('bookmarks')
               ->onDelete('cascade');

           $table->foreign('category_id')
               ->references('id')
               ->on('categories')
               ->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookmarks_categories', function (Blueprint $table) {
            $table->dropForeign('bookmarks_categories_user_id_foreign');
            $table->dropForeign('bookmarks_categories_bookmark_id_foreign');
            $table->dropForeign('bookmarks_categories_category_id_foreign');
        });

        Schema::drop('bookmarks_categories');
    }
}
