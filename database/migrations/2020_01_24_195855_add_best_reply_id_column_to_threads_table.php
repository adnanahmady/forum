<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBestReplyIdColumnToThreadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('threads', function (Blueprint $table) {
            if (!Schema::hasColumn('threads', 'best_reply_id')) {
                $table->unsignedBigInteger('best_reply_id')->nullable();

                $table->foreign(['best_reply_id'])
                    ->references('id')
                    ->on('replies')
                    ->onDelete('SET NULL');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!app()->environment('testing')) {
            Schema::table('threads', function (Blueprint $table) {
                $table->dropForeign(['best_reply_id']);
                $table->dropColumn('best_reply_id');
            });
        }
    }
}
