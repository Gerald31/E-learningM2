<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTutoratTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tutorat', function (Blueprint $table) {
            $table->integer('tutorat_id')->autoIncrement();
            $table->unsignedInteger('class_level_id');
            $table->unsignedInteger('subject_id');
            $table->unsignedBigInteger('prof_id')->nullable();
            $table->unsignedBigInteger('etudiant_id')->nullable();
            $table->text('description')->nullable();
            $table->string('documents')->nullable();
            $table->string('subject')->nullable();
            $table->date('date');
            $table->time('time_start');
            $table->time('time_end');
            $table->double('price')->default(0.0);
            $table->timestamps();

            $table->foreign('class_level_id')->references('class_level_id')->on('class_level')->cascadeOnDelete();
            $table->foreign('subject_id')->references('subject_id')->on('subject')->cascadeOnDelete();
            $table->foreign('prof_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('etudiant_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tutorat');
    }
}
