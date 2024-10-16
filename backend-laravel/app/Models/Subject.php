<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'subject';
    protected $primaryKey = 'subject_id';

    /**
     * @var string[]
     */
    protected $fillable = [
        'subject_name',
        'class_level_id',
    ];


    public function getAll() {
        return self::orderBy('Subject_Name', 'ASC')
                    ->orderBy('class_level_id', 'ASC')->get();
}
}
