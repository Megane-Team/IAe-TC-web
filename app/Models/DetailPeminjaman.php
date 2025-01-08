<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class DetailPeminjaman extends Model
{
    protected $table = 'detailPeminjamans';
    protected $fillable = [
        'status',
        'borrowedDate',
        'estimatedTime',
        'returnDate',
        'objective',
        'destination',
        'passenger',
        'canceledReason',
        'user_id',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    use HasFactory;
}
