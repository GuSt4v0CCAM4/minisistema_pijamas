<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/registroventa', [App\Http\Controllers\Sales\SalesRecordController::class, 'index'])->name('sales.record');
Route::get('/consultaventa', [App\Http\Controllers\Sales\SalesConsultController::class, 'index'])->name('sales.consult');
Route::post('/registro', [App\Http\Controllers\Sales\SalesRecordController::class, 'input'])->name('sales.record.store');
Route::get('/cashrecord', [App\Http\Controllers\Cash\CashRecordController::class, 'index'])->name('cash.record');
Route::post('/registrocaja', [App\Http\Controllers\Cash\CashRecordController::class, 'input'])->name('cash.record.store');
Route::get('/cashconsult', [App\Http\Controllers\Cash\CashConsultController::class, 'index'])->name('cash.consult');
Route::get('/cuadre', [App\Http\Controllers\CashBalanceController::class, 'index'])->name('cash.balance');
Route::get('/registroinventario', [App\Http\Controllers\Inventory\InventoryRegisterController::class, 'index'])->name('inventory.register');
Route::post('/inventoryregister',[App\Http\Controllers\Inventory\InventoryRegisterController::class, 'register'])->name('product.register');
Route::get('/get_products', [App\Http\Controllers\Sales\SalesRecordController::class, 'get_products'])->name('get.products');
Route::get('/get_products_details', [App\Http\Controllers\Sales\SalesRecordController::class, 'get_products_details'])->name('get.product.details');
Route::get('/consultainventario', [App\Http\Controllers\Inventory\InventoryConsultController::class, 'index'])->name('inventory.consult');
