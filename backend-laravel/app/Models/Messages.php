<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'messages';
    protected $primaryKey = 'id';

    /**
     * @var string[]
     */
    protected $fillable = [
        'type',
        'from_id',
        'tutorat_id',
        'body',
        'attachment',
        'seen',
        'reply_to'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'from_id');
    }

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
    public function replyTo()
    {
        return $this->belongsTo(self::class, 'reply_to');
    }
}
