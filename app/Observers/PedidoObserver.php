<?php

namespace App\Observers;

use App\Models\Pedido;
use App\Service\DashboardStatsService;

class PedidoObserver
{
    /**
     * Handle the Pedido "created" event.
     */
    public function created(Pedido $pedido): void
    {
        $this->forgetDashboardAggregates();
    }

    /**
     * Handle the Pedido "updated" event.
     */
    public function updated(Pedido $pedido): void
    {
        $this->forgetDashboardAggregates();
    }

    /**
     * Handle the Pedido "deleted" event.
     */
    public function deleted(Pedido $pedido): void
    {
        $this->forgetDashboardAggregates();
    }

    /**
     * Handle the Pedido "restored" event.
     */
    public function restored(Pedido $pedido): void
    {
        $this->forgetDashboardAggregates();
    }

    /**
     * Handle the Pedido "force deleted" event.
     */
    public function forceDeleted(Pedido $pedido): void
    {
        $this->forgetDashboardAggregates();
    }

    private function forgetDashboardAggregates(): void
    {
        DashboardStatsService::forgetCache();
    }
}
