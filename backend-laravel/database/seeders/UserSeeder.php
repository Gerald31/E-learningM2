<?php

namespace Database\Seeders;

use App\Models\BankingInformation;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        User::truncate();
        Schema::enableForeignKeyConstraints();

        // User Admin
        User::updateOrCreate(
            [
                'id' => 1
            ],
            [
                "firstname"=> "ADMIN",
                "lastname"=> "admin",
                "email"=> "admin@admin.com",
                "password"=> Hash::make('admin2022'),
                "roles"=> User::ROLE_ADMIN,
                "status"=> true,
                "city"=> "Tana",
                "code_postal"=> "101",
                "sexe"=> "M",
            ]
        );


        // User Admin
        User::updateOrCreate(
            [
                'id' => 5
            ],
            [
                "firstname"=> "GERALD",
                "lastname"=> "DevOps",
                "email"=> "admin@gmail.com",
                "password"=> Hash::make('devops.com'),
                "roles"=> User::ROLE_ADMIN,
                "status"=> true,
                "city"=> "Fianarantsoa",
                "code_postal"=> "301",
                "sexe"=> "M",
            ]
        );


        // User Tuteur
        $tutor = User::updateOrCreate(
            [
                'id' => 2
            ],
            [
                "firstname"=> "TUTOR",
                "lastname"=> "Tuteur",
                "email"=> "tutor@tutor.com",
                "password"=> Hash::make('tutor2022'),
                "roles"=> User::ROLE_PROF,
                "status"=> true,
                "city"=> "Paris",
                "code_postal"=> "75005",
                "phone"=> "+33973261678",
                "address"=> "Rue France - Brocante, 7 r Patriarches",
                "stripe_customer"=> "acct_1MCNxPHCi4sAZc10",
                "sexe"=> "M",
            ]
        );

        //Tuteur BankInformation
        BankingInformation::create([
            'iban' => 'FR1420041010050500013M02606',
            'user_id' => $tutor->id,
        ]);

        // User Student
        $student = User::updateOrCreate(
            [
                'id' => 3
            ],
            [
                "firstname"=> "STUDENT",
                "lastname"=> "Student",
                "email"=> "student@student.com",
                "password"=> Hash::make('student2022'),
                "roles"=> User::ROLE_ETUDIANT,
                "status"=> true,
                "city"=> "Tana",
                "code_postal"=> "75005",
                "phone"=> "+261341000099",
                "address"=> "Rue Boulevard",
                "sexe"=> "M",
            ]
        );
    }
}
