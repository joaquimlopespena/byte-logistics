<?php

namespace App\Repositories;

use App\Models\Transportadora;
use App\Repositories\Contracts\TransportadoraRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class TransportadoraRepository implements TransportadoraRepositoryInterface
{
    public function paginateOrdered(int $perPage): LengthAwarePaginator
    {
        return Transportadora::query()
            ->orderBy('nome', 'asc')
            ->paginate($perPage);
    }

    public function findById(int $id): ?Transportadora
    {
        return Transportadora::query()->findOrFail($id);
    }

    public function all(): Collection
    {
        return Transportadora::query()->orderBy('nome', 'asc')->get();
    }
}
