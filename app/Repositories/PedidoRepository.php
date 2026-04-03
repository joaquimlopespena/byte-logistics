<?php

namespace App\Repositories;

use App\Models\Pedido;
use App\Repositories\Contracts\PedidoRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PedidoRepository implements PedidoRepositoryInterface
{
    public function paginateOrdered(int $perPage): LengthAwarePaginator
    {
        return Pedido::query()
            ->with('transportadora')
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function findByIdWithTransportadora(int $id): ?Pedido
    {
        return Pedido::query()
            ->with('transportadora')
            ->find($id);
    }

    public function create(array $attributes): Pedido
    {
        return Pedido::query()->create($attributes);
    }
}
