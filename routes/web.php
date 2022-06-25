<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\AccountController;

use App\Http\Controllers\Admin;

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

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::group(['prefix' => 'user', 'controller' => UserController::class], function () {
    Route::get('/login', 'login')->name('login');
    Route::post('/login', 'postLogin');
    Route::get('/profile', 'profile')->middleware('auth')->name('profile');
    Route::get('/logout', 'logout')->middleware('auth');
});

Route::group(['prefix' => 'bank', 'controller' => BankController::class], function () {
    Route::get('/viewAll', 'viewAll')->name('banks');
    Route::get('/view/{id}', 'view');
    Route::get('/view/{bank}/photo', 'viewPhoto');
});

Route::group(['prefix' => 'account', 'controller' => AccountController::class], function () {
    Route::middleware('auth')->group(function () {
        Route::get('/viewAll', 'viewAll')->name('accounts');
        Route::get('/view/{id}', 'view');
        Route::get('/transaction/{id}/viewAll', 'viewAllTransactions');
        Route::get('/deposit/{id}', 'deposit');
        Route::post('/deposit/{id}', 'postDeposit');
        Route::post('/deposit/{id}/confirm', 'postDepositConfirm');
        Route::get('/withdraw/{id}', 'withdraw');
        Route::post('/withdraw/{id}', 'postWithdraw');
        Route::post('/withdraw/{id}/confirm', 'postWithdrawConfirm');
        Route::get('/transfer/{id}', 'transfer');
        Route::post('/transfer/{id}', 'postTransfer');
        Route::post('/transfer/{id}/confirm', 'postTransferConfirm');

        Route::get('/open', 'open');
        Route::post('/open', 'postOpen');
        Route::post('/close/{id}', 'close');
    });
});

Route::prefix('admin')->group(function () {
    Route::get('/', function () {
        return redirect('admin/bank/viewAll');
    });

    Route::controller(Admin\AuthController::class)->group(function () {
        Route::get('/login', 'login')->name('login');
        Route::post('/login', 'postLogin');
        Route::get('/logout', 'logout')->middleware('auth:admin');
    });

    Route::middleware('auth:admin')->group(function () {
        Route::group(['prefix' => 'bank', 'controller' => Admin\BankController::class], function () {
            Route::get('/add', 'add');
            Route::post('/add', 'postAdd');

            Route::get('/viewAll', 'viewAll');

            Route::get('/transferFee/{bank}/viewAll', 'viewAllTransferFee');
            Route::post('/transferFee/{bank}/add', 'addTransferFee');
            Route::post('/transferFee/{bank}/remove/{dstbank}', 'removeTransferFee');

            Route::get('/edit/{bank}', 'edit');
            Route::post('/edit/{bank}', 'postEdit');
            Route::post('/edit/{bank}/removephoto', 'removePhoto');

            Route::post('/remove/{bank}', 'remove');
        });
    });
});

