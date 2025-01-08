<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Perangkat extends Model
{
    protected $table = 'perangkats';
    protected $fillable = [
        'deviceToken',
        'user_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    use HasFactory;

}
