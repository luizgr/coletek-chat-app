<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guild extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'user_id',
    ];

    /**
     * Get the user that owns the guild.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the channels for the guild.
     */
    public function channels()
    {
        return $this->hasMany(Channel::class);
    }
}
