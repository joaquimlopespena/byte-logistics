<?php

namespace App\Providers;

use App\Models\PersonalAccessToken;
use App\Repositories\Contracts\PedidoRepositoryInterface;
use App\Repositories\Contracts\TransportadoraRepositoryInterface;
use App\Repositories\PedidoRepository;
use App\Repositories\TransportadoraRepository;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PedidoRepositoryInterface::class, PedidoRepository::class);
        $this->app->bind(TransportadoraRepositoryInterface::class, TransportadoraRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFour();

        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
    }
}
