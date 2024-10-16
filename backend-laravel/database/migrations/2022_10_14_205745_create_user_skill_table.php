<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSkillTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_skill', function (Blueprint $table) {
            $table->unsignedInteger('user_skill_id')->autoIncrement();
            $table->unsignedBigInteger('user_id');
            $table->unsignedInteger('subject_id')->nullable();
            $table->unsignedInteger('class_level_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('subject_id')->references('subject_id')->on('subject')->cascadeOnDelete();
            $table->foreign('class_level_id')->references('class_level_id')->on('class_level')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_skill');
    }
}
