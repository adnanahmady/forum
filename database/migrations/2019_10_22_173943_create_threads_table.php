<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThreadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('threads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('channel_id');
            $table->unsignedBigInteger('replies_count')->default(0);
            $table->text('body');
            $table->string('title');
            $table->string('slug')->unique();
            $table->timestamp('locked')->nullable();
            $table->timestamps();

            if (app()->environment('testing')) {
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
        Schema::dropIfExists('threads');
    }
}
