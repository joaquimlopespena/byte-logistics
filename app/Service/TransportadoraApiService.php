<?php

namespace App\Service;

use App\Models\Transportadora;
use App\Repositories\Contracts\TransportadoraRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class TransportadoraApiService
{
    public function __construct(
        private readonly TransportadoraRepositoryInterface $transportadoraRepository
    ) {}

    public function listarPaginado(int $perPage): LengthAwarePaginator
    {
        return $this->transportadoraRepository->paginateOrdered($perPage);
    }

    public function obter(int $id): Transportadora
    {
        return $this->transportadoraRepository->findById($id);
    }

    public function listarTodas(): Collection
    {
        return $this->transportadoraRepository->all();
    }

}
