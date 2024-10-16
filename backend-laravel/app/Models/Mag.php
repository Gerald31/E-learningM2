<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mag extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'mag';
    protected $primaryKey = 'mag_id';

    /**
     * @var string[]
     */
    protected $fillable = [
        'title',
        'sub_title',
        'content',
        'picture',
        'enable',
    ];

    protected $casts = [
        'enable' => 'boolean',
    ];


    /**
     * Select active mags
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeOfActif($query): Builder
    {
        return $query->where('enable', true);
    }

    /**
     * @return mixed
     */
    public function getAllMags() {
        return self::orderBy('created_at', 'DESC')
            ->get();
    }
}
