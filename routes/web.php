<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HamaController;
use App\Http\Controllers\InspeksiController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\MetodeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TindakanController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin routes
Route::middleware(['admin', 'auth'])->prefix('admin')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::post('/users/store', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    //--datatables
    Route::get('/users/data', [UserController::class, 'data'])->name('admin.users.data');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('master')->name('master.')->middleware('auth')->group(function () {
    //metode
    Route::get('/metode', [MetodeController::class, 'index'])->name('metode.index');
    Route::get('/metode/create', [MetodeController::class, 'create'])->name('metode.create');
    Route::post('/metode', [MetodeController::class, 'store'])->name('metode.store');
    // Route::get('/metode/{id}', [MetodeController::class, 'show'])->name('metode.show');
    // Route::get('/metode/{id}/edit', [MetodeController::class, 'edit'])->name('metode.edit');
    Route::patch('/metode/{id}', [MetodeController::class, 'update'])->name('metode.update');
    Route::delete('/metode/{id}', [MetodeController::class, 'destroy'])->name('metode.destroy');
    //--datatables
    Route::get('/metode/data', [MetodeController::class, 'data'])->name('metode.data');
    //--select2
    Route::get('/metode/select2', [MetodeController::class, 'select2Data'])->name('metode.select2');

    //lokasi
    Route::get('/lokasi', [LokasiController::class, 'index'])->name('lokasi.index');
    Route::get('/lokasi/create', [LokasiController::class, 'create'])->name('lokasi.create');
    Route::post('/lokasi', [LokasiController::class, 'store'])->name('lokasi.store');
    Route::patch('/lokasi/{id}', [LokasiController::class, 'update'])->name('lokasi.update');
    Route::delete('/lokasi/{id}', [LokasiController::class, 'destroy'])->name('lokasi.destroy');
    //--datatables
    Route::get('/lokasi/data', [LokasiController::class, 'data'])->name('lokasi.data');
    //--select2
    Route::get('/lokasi/select2', [LokasiController::class, 'select2Data'])->name('lokasi.select2');

    //hama
    Route::get('/hama', [HamaController::class, 'index'])->name('hama.index');
    Route::get('/hama/create', [HamaController::class, 'create'])->name('hama.create');
    Route::post('/hama', [HamaController::class, 'store'])->name('hama.store');
    Route::patch('/hama/{id}', [HamaController::class, 'update'])->name('hama.update');
    Route::delete('/hama/{id}', [HamaController::class, 'destroy'])->name('hama.destroy');
    //--datatables
    Route::get('/hama/data', [HamaController::class, 'data'])->name('hama.data');
    //--select2
    Route::get('/hama/select2', [HamaController::class, 'select2Data'])->name('hama.select2');

    //tindakan
    Route::get('/tindakan', [TindakanController::class, 'index'])->name('tindakan.index');
    Route::get('/tindakan/create', [TindakanController::class, 'create'])->name('tindakan.create');
    Route::post('/tindakan', [TindakanController::class, 'store'])->name('tindakan.store');
    Route::patch('/tindakan/{id}', [TindakanController::class, 'update'])->name('tindakan.update');
    Route::delete('/tindakan/{id}', [TindakanController::class, 'destroy'])->name('tindakan.destroy');
    //--datatables
    Route::get('/tindakan/data', [TindakanController::class, 'data'])->name('tindakan.data');
    //--input table in inspeksi
    Route::get('/tindakan/data-input-table', [TindakanController::class, 'dataInputTable'])->name('tindakan.data-input-table');
    Route::get('/tindakan/data-input-table/{hama_id}', [TindakanController::class, 'dataInputTable'])->name('tindakan.data-input-table');

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //inspeksi
    //tindakan
    Route::get('/inspeksi', [InspeksiController::class, 'index'])->name('inspeksi.index');
    Route::get('/inspeksi/create', [InspeksiController::class, 'create'])->name('inspeksi.create');
    Route::post('/inspeksi/store', [InspeksiController::class, 'store'])->name('inspeksi.store');
    Route::patch('/inspeksi/{id}', [InspeksiController::class, 'update'])->name('inspeksi.update');
    Route::delete('/inspeksi/{id}', [InspeksiController::class, 'destroy'])->name('inspeksi.destroy');
    //--datatables
    Route::get('/inspeksi/data', [InspeksiController::class, 'data'])->name('inspeksi.data');
    Route::get('/inspeksi/dataperbulan', [InspeksiController::class, 'dataperbulan'])->name('inspeksi.dataperbulan');
    Route::get('/inspeksi/datapermetode', [InspeksiController::class, 'datapermetode'])->name('inspeksi.datapermetode');

    //dashboard
    Route::get('/dashboard/linechart', [DashboardController::class, 'linechart'])->name('dashboard.linechart');
    Route::get('/dashboard/areachart', [DashboardController::class, 'areachart'])->name('dashboard.areachart');
    Route::get('/dashboard/donutchart', [DashboardController::class, 'donutchart'])->name('dashboard.donutchart');

});

require __DIR__ . '/auth.php';
