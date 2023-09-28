<?php

namespace App\Models;

use App\Events\MessageCreated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'text', 'user_id', 'configuration'
    ];

    protected $casts = [
        'configuration' => 'array'
    ];

    protected $dispatchesEvents = [
        'created' => MessageCreated::class
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
