<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\HeadOffice\HeadOfficeController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\TempatController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\DetailPeminjamanController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Route::get('/', function () {
//    return view('welcome');
// });

Route::get('/', function () {
   return redirect()->route('login');
});

Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login');

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login')->with('success', 'Anda berhasil logout.');
})->name('logout');

Route::middleware('auth', )->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

// headoffice routes
Route::middleware(['auth', 'headofficeMiddleware'])->group(function () {
    Route::get('/headoffice/profile', [HeadOfficeController::class, 'editProfile'])->name('headoffice.profile.edit');
    Route::put('/headoffice/profile', [HeadOfficeController::class, 'updateProfile'])->name('headoffice.profile.update');

    Route::get('/peminjaman/pdf', [PeminjamanController::class, 'downloadPDF'])->name('peminjaman.downloadPDF');
    Route::get('/detailpeminjaman/pdf', [DetailPeminjamanController::class, 'downloadPDF'])->name('detailpeminjaman.downloadPDF');
    Route::get('peminjaman/export', [PeminjamanController::class, 'exportExcel'])->name('peminjaman.exportExcel');
    Route::get('detailpeminjaman/export', [DetailPeminjamanController::class, 'exportExcel'])->name('detailpeminjaman.exportExcel');

    Route::get('/headoffice/dashboard', [HeadOfficeController::class, 'index'])->name('headoffice.dashboard');
    Route::get('/headoffice/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('/headoffice/peminjaman/{id}', [PeminjamanController::class, 'show'])->name('peminjaman.show');
    Route::get('/headoffice/detailpeminjaman', [DetailPeminjamanController::class, 'index'])->name('detailpeminjaman.index');
    Route::get('/headoffice/detailpeminjaman/{detailpeminjaman}', [DetailPeminjamanController::class, 'show'])->name('detailpeminjaman.show');

    Route::post('/headoffice/detailpeminjaman/{detailpeminjaman}/approve', [DetailPeminjamanController::class, 'approve'])->name('detailpeminjaman.approve');
    Route::get('/headoffice/detailpeminjaman/{detailpeminjaman}/reject', [DetailPeminjamanController::class, 'rejectForm'])->name('detailpeminjaman.reject');
    Route::put('/headoffice/detailpeminjaman/{detailpeminjaman}/reject', [DetailPeminjamanController::class, 'reject'])->name('detailpeminjaman.reject.update');

});

// admin routes
Route::middleware(['auth', 'adminMiddleware'])->group(function () {
    Route::get('/admin/profile', [AdminController::class, 'editProfile'])->name('admin.profile.edit');
    Route::put('/admin/profile', [AdminController::class, 'updateProfile'])->name('admin.profile.update');

    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::resource('admin/ruangan', RuanganController::class);
    Route::resource('admin/kendaraan', KendaraanController::class);
    Route::resource('admin/tempat', TempatController::class);
    Route::resource('admin/user', UserController::class);
    Route::resource('/admin/barang', BarangController::class);

    Route::get('/admin/logs', [LogController::class, 'index'])->name('logs.index');
    Route::get('/tempat/parkiran', [TempatController::class, 'parkiran'])->name('tempat.parkiran');
    Route::get('/tempat/gedung', [TempatController::class, 'gedung'])->name('tempat.gedung');

    Route::post('/tempat/bulk-delete', [TempatController::class, 'bulkDelete'])->name('tempat.bulkDelete');
    Route::post('/ruangan/bulk-delete', [RuanganController::class, 'bulkDelete'])->name('ruangan.bulkDelete');
    Route::post('/barang/bulk-delete', [BarangController::class, 'bulkDelete'])->name('barang.bulkDelete');
    Route::post('/kendaraan/bulk-delete', [KendaraanController::class, 'bulkDelete'])->name('kendaraan.bulkDelete');
    Route::post('/user/bulk-delete', [UserController::class, 'bulkDelete'])->name('user.bulkDelete');

    Route::get('/tempat/pdf', [TempatController::class, 'downloadPDF'])->name('tempat.downloadPDF');
    Route::get('/ruangan/pdf', [RuanganController::class, 'downloadPDF'])->name('ruangan.downloadPDF');
    Route::get('/barang/pdf', [BarangController::class, 'downloadPDF'])->name('barang.downloadPDF');
    Route::get('/kendaraan/pdf', [KendaraanController::class, 'downloadPDF'])->name('kendaraan.downloadPDF');
    Route::get('/user/pdf', [UserController::class, 'downloadPDF'])->name('user.downloadPDF');

    //qr code
    Route::get('/ruangan/qrcode-pdf', [RuanganController::class, 'downloadQRCodePDF'])->name('ruangan.qrcode-pdf');
    Route::get('/admin/ruangan/{id}/qrcode-pdf', [RuanganController::class, 'downloadSingleQRCode'])->name('ruangan.qrcode.single-pdf');
    Route::get('/barang/qrcode-pdf', [BarangController::class, 'downloadQRCodePDF'])->name('barang.qrcode-pdf');
    Route::get('/admin/barang/{id}/qrcode-pdf', [BarangController::class, 'downloadSingleQRCode'])->name('barang.qrcode.single-pdf');
    Route::get('/kendaraan/qrcode-pdf', [KendaraanController::class, 'downloadQRCodePDF'])->name('kendaraan.qrcode-pdf');
    Route::get('/admin/kendaraan/{id}/qrcode-pdf', [KendaraanController::class, 'downloadSingleQRCode'])->name('kendaraan.qrcode.single-pdf');

    Route::get('tempat/export', [TempatController::class, 'exportExcel'])->name('tempat.exportExcel');
    Route::post('tempat/import', [TempatController::class, 'import'])->name('tempat.import');
    Route::get('tempat/import', [TempatController::class, 'showImportForm'])->name('tempat.showImportForm');
    Route::get('tempat/download-sample', [TempatController::class, 'downloadSample'])->name('tempat.downloadSample');

    // Rute untuk Ruangan
    Route::get('ruangan/export', [RuanganController::class, 'exportExcel'])->name('ruangan.exportExcel');
    Route::post('ruangan/import', [RuanganController::class, 'import'])->name('ruangan.import');
    Route::get('ruangan/import', [RuanganController::class, 'showImportForm'])->name('ruangan.showImportForm');
    Route::get('ruangan/download-sample', [RuanganController::class, 'downloadSample'])->name('ruangan.downloadSample');

    // Rute untuk Barang
    Route::get('barang/export', [BarangController::class, 'exportExcel'])->name('barang.exportExcel');
    Route::post('barang/import', [BarangController::class, 'import'])->name('barang.import');
    Route::get('barang/import', [BarangController::class, 'showImportForm'])->name('barang.showImportForm');
    Route::get('barang/download-sample', [BarangController::class, 'downloadSample'])->name('barang.downloadSample');

    // Rute untuk Kendaraan
    Route::get('kendaraan/export', [KendaraanController::class, 'exportExcel'])->name('kendaraan.exportExcel');
    Route::post('kendaraan/import', [KendaraanController::class, 'import'])->name('kendaraan.import');
    Route::get('kendaraan/import', [KendaraanController::class, 'showImportForm'])->name('kendaraan.showImportForm');
    Route::get('kendaraan/download-sample', [KendaraanController::class, 'downloadSample'])->name('kendaraan.downloadSample');

    // Rute untuk User
    Route::get('user/export', [UserController::class, 'exportExcel'])->name('user.exportExcel');
    Route::post('user/import', [UserController::class, 'import'])->name('user.import');
    Route::get('user/import', [UserController::class, 'showImportForm'])->name('user.showImportForm');
    Route::get('user/download-sample', [UserController::class, 'downloadSample'])->name('user.downloadSample');

});