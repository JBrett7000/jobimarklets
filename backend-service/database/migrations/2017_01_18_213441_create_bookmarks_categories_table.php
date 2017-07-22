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
            $table->bigInteger('bookmark_id')->unsigned();
            $table->bigInteger('category_id')->unsigned();
        });


        Schema::table('bookmarks_categories', function (Blueprint $table) {
           $table->foreign('bookmark_id')
               ->references('id')
               ->on('bookmarks');

           $table->foreign('category_id')
                ->references('id')
                ->on('categories');
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
            $table->dropForeign('bookmarks_categories_bookmark_id_foreign');
            $table->dropForeign('bookmarks_categories_category_id_foreign');
        });

        Schema::drop('bookmarks_categories');
    }
}
