<?php

namespace Database\Seeders;

use App\Models\Subject;
use App\Models\UserSkill;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class UserSkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        UserSkill::truncate();
        Schema::enableForeignKeyConstraints();

        $userSkills = [
            [
                'user_id' => 2,
                'class_level_id' => 13,
                'subject_id' => Subject::where('class_level_id', 13)->get()->unique()->random()->subject_id,
            ],
            [
                'user_id' => 2,
                'class_level_id' => 13,
                'subject_id' => Subject::where('class_level_id', 13)->get()->unique()->random()->subject_id,
            ],
            [
                'user_id' => 2,
                'class_level_id' => 12,
                'subject_id' => Subject::where('class_level_id', 12)->get()->unique()->random()->subject_id,
            ],
            [
                'user_id' => 2,
                'class_level_id' => 7,
                'subject_id' => Subject::where('class_level_id', 7)->get()->unique()->random()->subject_id,
            ],
            [
                'user_id' => 2,
                'class_level_id' => 6,
                'subject_id' => Subject::where('class_level_id', 6)->get()->unique()->random()->subject_id,
            ],
            [
                'user_id' => 2,
                'class_level_id' => 6,
                'subject_id' => Subject::where('class_level_id', 6)->get()->unique()->random()->subject_id,
            ],
            [
                'user_id' => 3,
                'class_level_id' => 6,
                'subject_id' => null,
            ],

            [
                'user_id' => 3,
                'class_level_id' => 7,
                'subject_id' => null,
            ],

            [
                'user_id' => 3,
                'class_level_id' => 12,
                'subject_id' => null,
            ],

            [
                'user_id' => 3,
                'class_level_id' => 13,
                'subject_id' => null,
            ],
        ];

        UserSkill::insert($userSkills);
    }
}
