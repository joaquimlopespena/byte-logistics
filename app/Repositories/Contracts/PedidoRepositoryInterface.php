<?php

namespace App\Repositories\Contracts;

use App\Models\Pedido;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PedidoRepositoryInterface
{
    public function paginateOrdered(int $perPage): LengthAwarePaginator;

    public function findByIdWithTransportadora(int $id): ?Pedido;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Pedido;
}
