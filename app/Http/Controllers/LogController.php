<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;
use Carbon\Carbon;
class LogController extends Controller
{
    // Menampilkan daftar log untuk admin
    public function index()
    {
        $logs = Log::with('user')->get(); // Ambil semua log dari database dengan relasi user
        return view('admin.logs.index', compact('logs')); // Ganti dengan path view yang sesuai
    }
}