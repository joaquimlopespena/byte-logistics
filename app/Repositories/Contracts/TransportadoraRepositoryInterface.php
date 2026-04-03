<?php

namespace App\Repositories\Contracts;

use App\Models\Transportadora;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface TransportadoraRepositoryInterface
{
    public function paginateOrdered(int $perPage): LengthAwarePaginator;
    public function findById(int $id): ?Transportadora;
    public function all(): Collection;
}
