<?php

namespace Database\Seeders;

use App\Models\ClassLevel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ClassLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        ClassLevel::truncate();
        Schema::enableForeignKeyConstraints();
        $classLevels =  [
            [
                'class_level_id' => 1,
                'label' => '2nde Générale et Technologique',
                'niveau' => ClassLevel::NIVEAU_LYCEE
            ],
            [
                'class_level_id' => 2,
                'label' => '2nde Professionnelle',
                'niveau' => ClassLevel::NIVEAU_LYCEE
            ],
            [
                'class_level_id' => 3,
                'label' => '1ère Générale',
                'niveau' => ClassLevel::NIVEAU_LYCEE
            ],
            [
                'class_level_id' => 4,
                'label' => '1ère Technologique',
                'niveau' => ClassLevel::NIVEAU_LYCEE
            ],
            [
                'class_level_id' => 5,
                'label' => '1ère Professionnelle',
                'niveau' => ClassLevel::NIVEAU_LYCEE
            ],
            [
                'class_level_id' => 6,
                'label' => 'Tale Générale',
                'niveau' => ClassLevel::NIVEAU_LYCEE
            ],
            [
                'class_level_id' => 7,
                'label' => 'Tale Technologique',
                'niveau' => ClassLevel::NIVEAU_LYCEE
            ],
            [
                'class_level_id' => 8,
                'label' => 'Tale Professionnelle',
                'niveau' => ClassLevel::NIVEAU_LYCEE
            ],
            [
                'class_level_id' => 9,
                'label' => 'CAP',
                'niveau' => ClassLevel::NIVEAU_LYCEE
            ],
            [
                'class_level_id' => 10,
                'label' => '6ième',
                'niveau' => ClassLevel::NIVEAU_COLLEGE
            ],
            [
                'class_level_id' => 11,
                'label' => '5ième',
                'niveau' => ClassLevel::NIVEAU_COLLEGE
            ],
            [
                'class_level_id' => 12,
                'label' => '4ième',
                'niveau' => ClassLevel::NIVEAU_COLLEGE
            ],
            [
                'class_level_id' => 13,
                'label' => '3ième',
                'niveau' => ClassLevel::NIVEAU_COLLEGE
            ]
        ];

        ClassLevel::insert($classLevels);
    }
}
