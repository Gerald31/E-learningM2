<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassLevel extends Model
{
    use HasFactory;

    protected $table = 'class_level';
    protected $primaryKey = 'class_level_id';

    /**
     * @var string[]
     */
    protected $fillable = [
        'label',
        'niveau',
    ];

    const NIVEAU_COLLEGE = 1; // Collège
    const NIVEAU_LYCEE = 2; // Lycée
    const NIVEAU_UNIVERSITE = 3; // Université

    /**
     * @return mixed
     */
    public function getAll() {
        return self::orderBy('label', 'ASC')->get();
    }
}
