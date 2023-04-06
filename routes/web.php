<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome',  ['url' => env('CRM_URL')]);
});

Route::get('/create', [\App\Http\Controllers\PostController::class, 'create']);
//Route::get('/test', [\App\Http\Controllers\PostController::class, 'index']);
//Route::get('/product', [\App\Http\Controllers\OzonController::class, 'getProductInfo']);
Route::get('/wb', [\App\Jobs\wbUpload::class, 'handle'])->name('wb');
Route::get('/ozon', [\App\Jobs\OzonUpload::class, 'handle'])->name('ozon');


Route::get('/ozon/ozonupload', function () {
    return view('ozon');
});
