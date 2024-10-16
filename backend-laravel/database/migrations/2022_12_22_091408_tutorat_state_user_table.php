<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TutoratStateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tutorat_state_user', function (Blueprint $table) {
            $table->unsignedInteger('tutorat_state_id')->autoIncrement();
            $table->unsignedBigInteger('users_id')->nullable();
            $table->integer('tutorat_id')->nullable();
            $table->boolean('affected')->default(false);
            $table->string('invoice')->nullable();
            $table->string('reference')->nullable();
            $table->timestamps();

            $table->foreign('users_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('tutorat_id')->references('tutorat_id')->on('tutorat')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tutorat_state_user');
    }
}
