<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tutorat extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'tutorat';
    protected $primaryKey = 'tutorat_id';

    /**
     * @var string[]
     */
    protected $fillable = [
        'class_level_id',
        'subject_id',
        'prof_id',
        'etudiant_id',
        'description',
        'documents',
        'subject',
        'date',
        'time_start',
        'time_end',
        'price',
    ];

    public function classLevel()
    {
        return $this->belongsTo(ClassLevel::class, 'class_level_id');
    }

    public function subjectClass()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'etudiant_id');
    }

    public function tutor()
    {
        return $this->belongsTo(User::class, 'prof_id');
    }

    public function tutoratStates()
    {
        return $this->hasMany(TutoratState::class, 'tutorat_id');
    }

    /**
     * @return mixed
     */
    public function getTutoratQuery() {
        return self::select([
            'tutorat_id',
            'date',
            'time_start',
            'time_end',
            'price',
            'created_at',
            'updated_at',
        ]);
    }
}
