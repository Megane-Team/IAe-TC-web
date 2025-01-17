<?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ApiController::class, 'index']);
Route::get('/checkstatus' , [ApiController::class, 'checkStatus']);
Route::post('/detailpeminjaman' , [ApiController::class, 'storeDetailPeminjaman']);
Route::post('/peminjaman' , [ApiController::class, 'storePeminjaman']);
