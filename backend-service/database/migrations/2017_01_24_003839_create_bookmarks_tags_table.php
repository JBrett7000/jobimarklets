<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookmarksTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookmarks_tags', function (Blueprint $table) {
            $table->integer('bookmark_id')->unsigned();
            $table->integer('tag_id')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookmarks_tags', function (Blueprint $table) {
            $table->dropForeign('bookmarks_tags_bookmark_id_foreign');
            $table->dropForeign('bookmarks_tags_tag_id_foreign');
        });

        Schema::drop('bookmarks_tags');
    }
}
