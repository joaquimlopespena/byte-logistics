<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PedidoController;
use App\Http\Controllers\Admin\PedidoExportController;
use App\Http\Controllers\Admin\TransportadoraController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Broadcast::routes(['middleware' => ['web', 'auth']]);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('admin')->group(function () {
        Route::get('pedidos/exportar', [PedidoExportController::class, 'create'])->name('admin.pedidos.export');
        Route::post('pedidos/exportar', [PedidoExportController::class, 'store'])->name('admin.pedidos.export.store');
        Route::get('pedidos/exportar/{pedidoExport}/baixar', [PedidoExportController::class, 'download'])->name('admin.pedidos.export.download');
        Route::resource('pedidos', PedidoController::class)->names('admin.pedidos');
        Route::resource('transportadoras', TransportadoraController::class)->names('admin.transportadoras');
    });
});

require __DIR__.'/auth.php';
