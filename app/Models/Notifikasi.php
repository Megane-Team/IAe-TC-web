<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notifikasi extends Model
{
    protected $table = 'notifikasis';

    // Jika Anda ingin menentukan kolom yang dapat diisi secara massal
    protected $fillable = [
        'user_id',
        'detailPeminjaman_id',
        'category',
        'isRead',
    ];

    // Definisikan relasi dengan model User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Definisikan relasi dengan model Peminjaman
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }
    use HasFactory;
}
