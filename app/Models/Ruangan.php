<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Ruangan extends Model
{
    protected $table = 'ruangans';
    protected $fillable = [
        'tempat_id',
        'code',
        'status',
        'capacity',
        'category',
        'photo',
    ];
    public function tempat()
    {
        return $this->belongsTo(Tempat::class);
    }
    use HasFactory;
}
