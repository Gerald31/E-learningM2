<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TutoratState extends Model
{
    /**
     * @var string
     */
    protected $table = 'tutorat_state_user';
    protected $primaryKey = 'tutorat_state_id';

    /**
     * @var string[]
     */
    protected $fillable = [
        'users_id',
        'tutorat_id',
        'price',
        'affected',
        'invoice',
        'reference',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tutorat()
    {
        return $this->belongsTo(Tutorat::class, 'tutorat_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function etudiant()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
