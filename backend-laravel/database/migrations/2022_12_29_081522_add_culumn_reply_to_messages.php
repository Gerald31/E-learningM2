<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCulumnReplyToMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign('messages_to_id_foreign');
            $table->dropColumn('to_id');

            $table->integer('tutorat_id')->nullable();
            $table->unsignedBigInteger('reply_to')->nullable();

            $table->foreign('tutorat_id')->references('tutorat_id')->on('tutorat')->cascadeOnDelete();
            $table->foreign('reply_to')->references('id')->on('messages')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign('tutorat_id');
            $table->dropForeign('reply_to');

            $table->dropColumn('tutorat_id');
            $table->dropColumn('reply_to');

            $table->unsignedBigInteger('to_id')->nullable();

            $table->foreign('to_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }
}
