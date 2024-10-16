<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankingInformation extends Model
{
    use HasFactory;
    protected $table = "banking_information";

    protected $fillable = [
        'user_id',
        'iban',
        'document'
    ];

    /**
     * Relation with one element of the table users
     *
     * @return BelongsTo
     */
    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
