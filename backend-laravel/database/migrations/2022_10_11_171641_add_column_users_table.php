<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table){
            $table->renameColumn('name','firstname');
            $table->string('lastname');
            $table->boolean('status')->default(false);
            $table->string('roles');
            $table->string('activation_token')->nullable();
            $table->string('avatar')->nullable();
            $table->string('city')->nullable();
            $table->string('code_postal')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('sexe')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table){
            $table->renameColumn('firstname','name');
            $table->removeColumn('lastname');
            $table->removeColumn('status');
            $table->removeColumn('roles');
            $table->removeColumn('activation_token');
            $table->removeColumn('avatar');
            $table->removeColumn('city');
            $table->removeColumn('code_postal');
            $table->removeColumn('phone');
            $table->removeColumn('address');
            $table->removeColumn('sexe');
        });
    }
}
