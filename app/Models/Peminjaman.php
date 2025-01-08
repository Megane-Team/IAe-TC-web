<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Peminjaman extends Model
{
    protected $table = 'peminjamans';
    protected $fillable = [
        'user_id',
        'ruangan_id',
        'barang_id',
        'kendaraan_id',
        'detailPeminjaman_id',
        'category',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Definisikan relasi dengan model Ruangan
    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class);
    }

    // Definisikan relasi dengan model Barang
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    // Definisikan relasi dengan model Kendaraan
    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class);
    }

    // Definisikan relasi dengan model DetailPeminjaman
    public function detailPeminjaman()
    {
        return $this->belongsTo(DetailPeminjaman::class);
    }
    use HasFactory;
}
