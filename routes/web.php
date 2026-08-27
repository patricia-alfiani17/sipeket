<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\PelatihController;
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboardController;
use App\Http\Controllers\Siswa\PengajuanMengulangController as SiswaPengajuanMengulangController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PendaftaranController;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.proses');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/pendaftaran', [PendaftaranController::class, 'create'])->name('pendaftaran.index');
Route::post('/pendaftaran', [PendaftaranController::class, 'store'])->name('pendaftaran.store');

Route::middleware('auth')->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/users', [DashboardController::class, 'users'])->name('users');
        Route::get('/users/create', [DashboardController::class, 'createUser'])->name('users.create');
        Route::post('/users', [DashboardController::class, 'storeUser'])->name('users.store');
        Route::get('/users/{user}/edit', [DashboardController::class, 'editUser'])->name('users.edit');
        Route::put('/users/{user}', [DashboardController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{user}', [DashboardController::class, 'destroyUser'])->name('users.destroy');

        Route::get('/pendaftaran', [DashboardController::class, 'pendaftaran'])->name('pendaftaran');
        Route::get('/pendaftaran/{pendaftaran}/akta', [DashboardController::class, 'showAkta'])->name('pendaftaran.akta');
        Route::get('/pendaftaran/{pendaftaran}/credentials', [DashboardController::class, 'getCredentials'])->name('pendaftaran.credentials');
        Route::post('/pendaftaran/{pendaftaran}/status', [DashboardController::class, 'updatePendaftaranStatus'])->name('pendaftaran.status');

        Route::get('/siswa', [App\Http\Controllers\Admin\SiswaController::class, 'index'])->name('siswa.index');
        Route::get('/siswa/create', [App\Http\Controllers\Admin\SiswaController::class, 'create'])->name('siswa.create');
        Route::post('/siswa', [App\Http\Controllers\Admin\SiswaController::class, 'store'])->name('siswa.store');
        Route::get('/siswa/{siswa}', [App\Http\Controllers\Admin\SiswaController::class, 'show'])->name('siswa.show');
        Route::get('/siswa/{siswa}/edit', [App\Http\Controllers\Admin\SiswaController::class, 'edit'])->name('siswa.edit');
        Route::put('/siswa/{siswa}', [App\Http\Controllers\Admin\SiswaController::class, 'update'])->name('siswa.update');
        Route::delete('/siswa/{siswa}', [App\Http\Controllers\Admin\SiswaController::class, 'destroy'])->name('siswa.destroy');

        Route::get('/pelatih', [App\Http\Controllers\Admin\PelatihController::class, 'index'])->name('pelatih.index');
        Route::get('/pelatih/create', [App\Http\Controllers\Admin\PelatihController::class, 'create'])->name('pelatih.create');
        Route::post('/pelatih', [App\Http\Controllers\Admin\PelatihController::class, 'store'])->name('pelatih.store');
        Route::get('/pelatih/{pelatih}', [App\Http\Controllers\Admin\PelatihController::class, 'show'])->name('pelatih.show');
        Route::get('/pelatih/{pelatih}/edit', [App\Http\Controllers\Admin\PelatihController::class, 'edit'])->name('pelatih.edit');
        Route::put('/pelatih/{pelatih}', [App\Http\Controllers\Admin\PelatihController::class, 'update'])->name('pelatih.update');
        Route::delete('/pelatih/{pelatih}', [App\Http\Controllers\Admin\PelatihController::class, 'destroy'])->name('pelatih.destroy');

        Route::get('/tingkat', [App\Http\Controllers\Admin\TingkatController::class, 'index'])->name('tingkat.index');
        Route::get('/tingkat/create', [App\Http\Controllers\Admin\TingkatController::class, 'create'])->name('tingkat.create');
        Route::post('/tingkat', [App\Http\Controllers\Admin\TingkatController::class, 'store'])->name('tingkat.store');
        Route::get('/tingkat/{tingkat}', [App\Http\Controllers\Admin\TingkatController::class, 'show'])->name('tingkat.show');
        Route::get('/tingkat/{tingkat}/edit', [App\Http\Controllers\Admin\TingkatController::class, 'edit'])->name('tingkat.edit');
        Route::put('/tingkat/{tingkat}', [App\Http\Controllers\Admin\TingkatController::class, 'update'])->name('tingkat.update');
        Route::delete('/tingkat/{tingkat}', [App\Http\Controllers\Admin\TingkatController::class, 'destroy'])->name('tingkat.destroy');

        Route::get('/tahun-periode', [App\Http\Controllers\Admin\TahunPeriodeController::class, 'index'])->name('tahun-periode.index');
        Route::get('/tahun-periode/create', [App\Http\Controllers\Admin\TahunPeriodeController::class, 'create'])->name('tahun-periode.create');
        Route::post('/tahun-periode', [App\Http\Controllers\Admin\TahunPeriodeController::class, 'store'])->name('tahun-periode.store');
        Route::get('/tahun-periode/{tahunPeriode}/edit', [App\Http\Controllers\Admin\TahunPeriodeController::class, 'edit'])->name('tahun-periode.edit');
        Route::put('/tahun-periode/{tahunPeriode}', [App\Http\Controllers\Admin\TahunPeriodeController::class, 'update'])->name('tahun-periode.update');
        Route::delete('/tahun-periode/{tahunPeriode}', [App\Http\Controllers\Admin\TahunPeriodeController::class, 'destroy'])->name('tahun-periode.destroy');

        Route::get('/materi-latihan', [App\Http\Controllers\Admin\MateriLatihanController::class, 'index'])->name('materi-latihan.index');
        Route::get('/materi-latihan/create', [App\Http\Controllers\Admin\MateriLatihanController::class, 'create'])->name('materi-latihan.create');
        Route::post('/materi-latihan', [App\Http\Controllers\Admin\MateriLatihanController::class, 'store'])->name('materi-latihan.store');
        Route::get('/materi-latihan/{materiLatihan}/edit', [App\Http\Controllers\Admin\MateriLatihanController::class, 'edit'])->name('materi-latihan.edit');
        Route::put('/materi-latihan/{materiLatihan}', [App\Http\Controllers\Admin\MateriLatihanController::class, 'update'])->name('materi-latihan.update');
        Route::delete('/materi-latihan/{materiLatihan}', [App\Http\Controllers\Admin\MateriLatihanController::class, 'destroy'])->name('materi-latihan.destroy');

        Route::get('/laporan', function () {
            return view('admin.laporan');
        })->name('laporan');

        Route::get('/laporan/export/siswa', [LaporanController::class, 'exportSiswa'])->name('laporan.export.siswa');
        Route::get('/laporan/export/evaluasi', [LaporanController::class, 'exportEvaluasi'])->name('laporan.export.evaluasi');
        Route::get('/laporan/export/riwayat', [LaporanController::class, 'exportRiwayat'])->name('laporan.export.riwayat');
    });

    Route::get('/pelatih/dashboard', [PelatihController::class, 'dashboard'])->name('pelatih.dashboard');

    Route::get('/pelatih/data-siswa', [PelatihController::class, 'dataSiswa'])->name('pelatih.data-siswa');
    Route::get('/pelatih/siswa/{siswa}', [PelatihController::class, 'showSiswa'])->name('pelatih.siswa.show');

    Route::get('/pelatih/input-nilai-harian', [PelatihController::class, 'inputNilaiHarian'])->name('pelatih.input-nilai-harian');
    Route::post('/pelatih/input-nilai-harian', [PelatihController::class, 'storeNilaiHarian'])->name('pelatih.nilai-harian.store');

    Route::get('/pelatih/input-nilai-ujian', [PelatihController::class, 'inputNilaiUjian'])->name('pelatih.input-nilai-ujian');
    Route::post('/pelatih/input-nilai-ujian', [PelatihController::class, 'storeNilaiUjian'])->name('pelatih.nilai-ujian.store');

    Route::get('/pelatih/evaluasi-kenaikan-tingkat', [PelatihController::class, 'evaluasiKenaikanTingkat'])->name('pelatih.evaluasi-kenaikan-tingkat');
    Route::post('/pelatih/evaluasi-kenaikan-tingkat', [PelatihController::class, 'storeEvaluasiKenaikanTingkat'])->name('pelatih.evaluasi-kenaikan-tingkat.store');
    Route::post('/pelatih/evaluasi-kenaikan-tingkat/tetapkan', [PelatihController::class, 'tetapkanEvaluasiKenaikanTingkat'])->name('pelatih.evaluasi-kenaikan-tingkat.tetapkan');

    Route::get('/pelatih/pengajuan-mengulang', [PelatihController::class, 'pengajuanMengulang'])->name('pelatih.pengajuan-mengulang');
    Route::post('/pelatih/pengajuan-mengulang/{pengajuan}/setujui', [PelatihController::class, 'setujuiPengajuanMengulang'])->name('pelatih.pengajuan-mengulang.setujui');
    Route::post('/pelatih/pengajuan-mengulang/{pengajuan}/tolak', [PelatihController::class, 'tolakPengajuanMengulang'])->name('pelatih.pengajuan-mengulang.tolak');

    Route::get('/siswa/dashboard', [SiswaDashboardController::class, 'index'])->name('siswa.dashboard');
    Route::get('/siswa/profil', [SiswaDashboardController::class, 'profil'])->name('siswa.profil');
    Route::get('/siswa/evaluasi', [SiswaDashboardController::class, 'evaluasi'])->name('siswa.evaluasi');
    Route::get('/siswa/riwayat', [SiswaDashboardController::class, 'riwayat'])->name('siswa.riwayat');
    Route::post('/siswa/pengajuan-mengulang', [SiswaPengajuanMengulangController::class, 'store'])->name('siswa.pengajuan-mengulang.store');

    Route::get('/profil', [UserController::class, 'profil'])->name('profil');
    Route::post('/profil/password', [UserController::class, 'updatePassword'])->name('profil.password');
    Route::post('/profil/foto', [UserController::class, 'updatePhoto'])->name('profil.foto');


    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::view('/siswa', 'siswa.index')->name('siswa.index');
    Route::view('/pelatih', 'pelatih.index')->name('pelatih.index');
    Route::view('/tingkat', 'tingkat.index')->name('tingkat.index');
    Route::view('/laporan', 'laporan.index')->name('laporan.index');
});
