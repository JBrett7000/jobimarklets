<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookmarksTagsKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookmarks_tags', function (Blueprint $table) {
            $table->foreign('bookmark_id')->references('id')
                ->on('bookmarks')
                ->onDelete('cascade');

            $table->foreign('tag_id')->references('id')
                ->on('tags')
                ->onDelete('cascade');
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
    }
}
