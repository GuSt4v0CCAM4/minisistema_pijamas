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

//Editar y elimina reg
Route::get('/editsale', [App\Http\Controllers\Sales\SalesConsultController::class, 'edit'])->name('sales.edit');
Route::post('/updatesale', [App\Http\Controllers\Sales\SalesConsultController::class, 'update'])->name('sales.update');
Route::get('/deletesale', [App\Http\Controllers\Sales\SalesConsultController::class, 'delete'])->name('sales.delete');

Route::get('/editcash', [App\Http\Controllers\Cash\CashConsultController::class, 'edit'])->name('cash.edit');
Route::post('/updatecash', [App\Http\Controllers\Cash\CashConsultController::class, 'update'])->name('cash.update');
Route::get('/deletecash', [App\Http\Controllers\Cash\CashConsultController::class, 'delete'])->name('cash.delete');

//BOX
Route::get('/boxregister', [App\Http\Controllers\Box\BoxRegisterController::class, 'index'])->name('box.register');
Route::post('/boxsale', [App\Http\Controllers\Box\BoxRegisterController::class, 'input_sale'])->name('box.register.sale');
Route::post('/boxcash', [App\Http\Controllers\Box\BoxRegisterController::class, 'input_cash'])->name('box.register.cash');
Route::get('/cashclose', [App\Http\Controllers\Box\BoxRegisterController::class, 'registerclose'])->name('cash.close');
Route::get('/closeboxregister', [App\Http\Controllers\CashBalanceController::class, 'register'])->name('closeboxregister');

Route::get('/rankingtrabajadores',[App\Http\Controllers\RankingWorkersController::class, 'index'])->name('workerranking');
