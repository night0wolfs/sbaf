<?php

namespace App\Models\Socials;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EveCharacter extends Model
{
    use HasFactory;

    protected $fillable = [
        'character_id',
        'character_name',
        'access_token',
        'refresh_token'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
