<?php

namespace App\Models\Socials;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscordAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'discord_id',
        'discord_user_name',
        'access_token',
        'refresh_token',
        'discriminator',
        'avatar',
        'verified'
    ];
}
