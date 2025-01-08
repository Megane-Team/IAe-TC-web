<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Barang extends Model
{
    protected $table = 'barangs';
    protected $fillable = [
        'name',
        'code',
        'status',
        'condition',
        'warranty',
        'photo',
        'ruangan_id',
    ];
    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class);
    }
    use HasFactory;
}
