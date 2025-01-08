<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Kendaraan extends Model
{
    protected $table = 'kendaraans';
    protected $fillable
    = [
           'name',
           'plat',
           'status',
           'condition',
           'warranty',
           'capacity',
           'category',
           'color',
           'photo',
           'tax',
           'tempat_id',
       ];
       public function tempat()
       {
           return $this->belongsTo(Tempat::class);
       }
       use HasFactory;
}
