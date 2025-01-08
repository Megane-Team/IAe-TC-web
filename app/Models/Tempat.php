<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Tempat extends Model
{
    protected $table = 'tempats';
    protected $fillable = [
        'name',
        'category',
        'photo',
    ];
    public function ruangans()
    {
        return $this->hasMany(Ruangan::class);
    }
    use HasFactory;
}
