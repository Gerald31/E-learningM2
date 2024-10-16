<?php

namespace Database\Seeders;

use App\Models\BankingInformation;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(ClassLevelSeeder::class);
        $this->call(SubjectSeeder::class);
        $this->call(UserSkillSeeder::class);
        $this->call(BankingInformationSeeder::class);
        User::factory(10)->create();
        BankingInformation::factory(2)->create();
    }
}
