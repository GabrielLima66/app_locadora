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
    return view('welcome');
});

Route::apiResource('cliente','App\Http\Controllers\ClienteController');
Route::apiResource('carro','App\Http\Controllers\CarroController');
Route::apiResource('locacao','App\Http\Controllers\LocacaoController');
Route::apiResource('marca','App\Http\Controllers\MarcaController');
Route::apiResource('modelo','App\Http\Controllers\ModeloController');
