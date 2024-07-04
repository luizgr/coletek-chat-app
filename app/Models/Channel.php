<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
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
        'guild_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the guild that owns the channel.
     */
    public function guild() 
    {
        return $this->belongsTo(Guild::class);
    }

    /**
     * Get the messages for the channel.
     */
    public function latestMessages()
    {
        return $this->hasMany(Message::class)->latest();
    }
}
