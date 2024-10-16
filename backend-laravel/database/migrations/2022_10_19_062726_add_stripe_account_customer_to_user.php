<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStripeAccountCustomerToUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('users', 'stripe_customer')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('stripe_customer')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('users', 'stripe_customer'))
        {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('stripe_customer');
            });
        }
    }
}
