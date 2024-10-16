<?php

namespace Database\Seeders;

use App\Models\BankingInformation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class BankingInformationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        BankingInformation::truncate();
        Schema::enableForeignKeyConstraints();
    }
}
