<?php

use App\Http\Controllers\Api\PedidoController;
use App\Http\Controllers\Api\SanctumTokenController;
use App\Http\Controllers\Api\TransportadoraController;
use Illuminate\Support\Facades\Route;

Route::post('/sanctum/token', [SanctumTokenController::class, 'store'])
    ->middleware('throttle:10,1');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/transportadoras', [TransportadoraController::class, 'index']);
    Route::get('/transportadoras/all', [TransportadoraController::class, 'all']);
    Route::get('/transportadoras/{id}', [TransportadoraController::class, 'show'])->whereNumber('id');

    Route::get('/pedidos', [PedidoController::class, 'index']);
    Route::get('/pedidos/{id}', [PedidoController::class, 'show'])->whereNumber('id');
    Route::post('/pedidos', [PedidoController::class, 'store']);
});
