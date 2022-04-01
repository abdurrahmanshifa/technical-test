<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CategoryTenantController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('login', [LoginController::class, 'index'])->name('login.index');
Route::post('login', [LoginController::class, 'index'])->name('login');

Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
    Route::get('/', function () {
        return redirect('dashboard');
    });
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    Route::group(['prefix'=>'master'], function () {
        Route::get('customer', [CustomerController::class, 'index'])->name('master.customer');
        Route::post('customer/simpan', [CustomerController::class, 'simpan'])->name('master.customer.simpan');
        Route::post('customer/ubah', [CustomerController::class, 'ubah'])->name('master.customer.ubah');
        Route::get('customer/data/{id}', [CustomerController::class, 'data'])->name('master.customer.data');
        Route::delete('customer/hapus/{id}', [CustomerController::class, 'hapus'])->name('master.customer.hapus');

        Route::middleware(['admin'])->group(function () {
            Route::get('category-tenant', [CategoryTenantController::class, 'index'])->name('master.category-tenant');
            Route::post('category-tenant/simpan', [CategoryTenantController::class, 'simpan'])->name('master.category-tenant.simpan');
            Route::post('category-tenant/ubah', [CategoryTenantController::class, 'ubah'])->name('master.category-tenant.ubah');
            Route::get('category-tenant/data/{id}', [CategoryTenantController::class, 'data'])->name('master.category-tenant.data');
            Route::delete('category-tenant/hapus/{id}', [CategoryTenantController::class, 'hapus'])->name('master.category-tenant.hapus');
    
            Route::get('tenant', [TenantController::class, 'index'])->name('master.tenant');
            Route::post('tenant/simpan', [TenantController::class, 'simpan'])->name('master.tenant.simpan');
            Route::post('tenant/ubah', [TenantController::class, 'ubah'])->name('master.tenant.ubah');
            Route::get('tenant/data/{id}', [TenantController::class, 'data'])->name('master.tenant.data');
            Route::delete('tenant/hapus/{id}', [TenantController::class, 'hapus'])->name('master.tenant.hapus');
    
    
            Route::get('user', [UserController::class, 'index'])->name('master.user');
            Route::post('user/simpan', [UserController::class, 'simpan'])->name('master.user.simpan');
            Route::post('user/ubah', [UserController::class, 'ubah'])->name('master.user.ubah');
            Route::get('user/data/{id}', [UserController::class, 'data'])->name('master.user.data');
            Route::delete('user/hapus/{id}', [UserController::class, 'hapus'])->name('master.user.hapus');
        });
    });

    Route::group(['prefix'=>'transaction'], function () {
        Route::get('tenant', [TransactionController::class, 'index'])->name('transaction.tenant');
        Route::post('tenant/simpan', [TransactionController::class, 'simpan'])->name('transaction.tenant.simpan');
        Route::post('tenant/ubah', [TransactionController::class, 'ubah'])->name('transaction.tenant.ubah');
        Route::get('tenant/data/{id}', [TransactionController::class, 'data'])->name('transaction.tenant.data');
        Route::delete('tenant/hapus/{id}', [TransactionController::class, 'hapus'])->name('transaction.tenant.hapus');
    });

    Route::get('file-view/{dir}/{filename}', [DashboardController::class, 'fileView']);
});

Auth::routes([
    'register'  => false,
    'reset'     => false,
    'verify'    => false,
    'login'     => false
]);