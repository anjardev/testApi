<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataSiswa;

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

Route::middleware(['auth'])->group(function () {
    Route::post('/mkt/api/siswa/import', [DataSiswa::class, 'import']);
    Route::get('/mkt/api/siswa/list', [DataSiswa::class, 'list']);
    Route::get('/mkt/api/siswa/kota', [DataSiswa::class, 'kota']);
});
