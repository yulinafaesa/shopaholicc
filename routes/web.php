<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Pembeli\BelanjaController;
use App\Http\Controllers\Pembeli\CheckoutController;
use App\Http\Controllers\Pembeli\KeranjangController;
use App\Http\Controllers\Pembeli\RuteController;
use App\Http\Controllers\Pembeli\TrackingController;
use App\Http\Controllers\Pembeli\TestimoniController;

use App\Http\Controllers\Penjual\DashboardController;
use App\Http\Controllers\Penjual\PengaturanController;
use App\Http\Controllers\Penjual\PesananController;
use App\Http\Controllers\Penjual\ProdukController;

use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| HALAMAN PUBLIK
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => inertia('Beranda'))->name('beranda');
Route::get('/tentang-kami', fn () => inertia('TentangKami'))->name('tentang-kami');
Route::get('/kontak', fn () => inertia('Kontak'))->name('kontak');

/*
|--------------------------------------------------------------------------
| TESTIMONI
|--------------------------------------------------------------------------
*/

// HALAMAN (lihat testimoni)
Route::get('/pembeli/testimoni', [TestimoniController::class, 'index']);
// KIRIM TESTIMONI (submit form)
Route::post('/pembeli/testimoni', [TestimoniController::class, 'store'])
    ->middleware(['auth', 'role:pembeli'])
    ->name('pembeli.testimoni.store');

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/masuk', [AuthController::class, 'showLogin'])->name('masuk');
    Route::post('/masuk', [AuthController::class, 'login'])->name('masuk.store');

    Route::get('/daftar', [AuthController::class, 'showRegister'])->name('daftar');
    Route::post('/daftar', [AuthController::class, 'register'])->name('daftar.store');
});

Route::post('/keluar', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('keluar');

/*
|--------------------------------------------------------------------------
| PEMBELI
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:pembeli'])
    ->prefix('pembeli')
    ->name('pembeli.')
    ->group(function () {

        Route::redirect('/', '/pembeli/belanja')->name('index');

        Route::get('/belanja', [BelanjaController::class, 'index'])->name('belanja');

        Route::get('/rute', [RuteController::class, 'index'])->name('rute');

        Route::get('/tracking', [TrackingController::class, 'index'])->name('tracking');

        Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang');
        Route::post('/keranjang', [KeranjangController::class, 'store'])->name('keranjang.store');
        Route::patch('/keranjang/{cartItem}', [KeranjangController::class, 'update'])->name('keranjang.update');
        Route::delete('/keranjang/{cartItem}', [KeranjangController::class, 'destroy'])->name('keranjang.destroy');

        Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
        Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
        Route::get('/checkout/sukses/{order}', [CheckoutController::class, 'success'])->name('checkout.sukses');

        Route::get('/akun', function (Request $request) {
            $user = $request->user();

            return inertia('pembeli/Akun', [
                'profile' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'address' => $user->address,
                    'city' => $user->city,
                    'postal_code' => $user->postal_code,
                ],
            ]);
        })->name('akun');
    });

/*
|--------------------------------------------------------------------------
| PENJUAL
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:penjual'])
    ->prefix('penjual')
    ->name('penjual.')
    ->group(function () {

        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/pesanan', [PesananController::class, 'index'])->name('pesanan');
        Route::get('/produk', [ProdukController::class, 'index'])->name('produk');
        Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan');
        Route::patch('/pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update');
    });

/*
|--------------------------------------------------------------------------
| API
|--------------------------------------------------------------------------
*/

Route::get('/api/products', [ProductController::class, 'index']);

/*
|--------------------------------------------------------------------------
| DB UTILITIES (PRODUCTION MIGRATIONS)
|--------------------------------------------------------------------------
*/
Route::get('/db-migrate', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        return '<h3>Database Migration:</h3><pre>' . \Illuminate\Support\Facades\Artisan::output() . '</pre>';
    } catch (\Throwable $e) {
        return '<h3>Migration Failed:</h3><pre>' . $e->getMessage() . "\n" . $e->getTraceAsString() . '</pre>';
    }
});

Route::get('/db-seed', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
        return '<h3>Database Seeded:</h3><pre>' . \Illuminate\Support\Facades\Artisan::output() . '</pre>';
    } catch (\Throwable $e) {
        return '<h3>Seeding Failed:</h3><pre>' . $e->getMessage() . "\n" . $e->getTraceAsString() . '</pre>';
    }
});

Route::get('/db-fresh-seed', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate:fresh', ['--force' => true, '--seed' => true]);
        return '<h3>Database Fresh & Seeded:</h3><pre>' . \Illuminate\Support\Facades\Artisan::output() . '</pre>';
    } catch (\Throwable $e) {
        return '<h3>Fresh Seed Failed:</h3><pre>' . $e->getMessage() . "\n" . $e->getTraceAsString() . '</pre>';
    }
});