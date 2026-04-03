<?php

namespace App\Service;

use App\Models\Pedido;
use App\Repositories\Contracts\PedidoRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class PedidoApiService
{
    public function __construct(
        private readonly PedidoRepositoryInterface $pedidoRepository
    ) {}

    public function listarPaginado(int $perPage): LengthAwarePaginator
    {
        return $this->pedidoRepository->paginateOrdered($perPage);
    }

    public function obter(int $id): Pedido
    {
        $pedido = $this->pedidoRepository->findByIdWithTransportadora($id);
        if ($pedido === null) {
            throw (new ModelNotFoundException)->setModel(Pedido::class, [$id]);
        }

        return $pedido;
    }

    /**
     * @param  array<string, mixed>  $data  Dados já validados (StorePedidoApiRequest).
     */
    public function cadastrar(array $data): Pedido
    {
        $preco = (float) $data['preco'];
        $quantidade = (int) $data['quantidade'];
        $total = round($preco * $quantidade, 2);

        return DB::transaction(function () use ($data, $preco, $quantidade, $total) {
            return $this->pedidoRepository->create([
                'descricao' => $data['descricao'] ?? null,
                'cliente_nome' => $data['cliente_nome'],
                'produto' => $data['produto'],
                'preco' => $preco,
                'quantidade' => $quantidade,
                'total' => $total,
                'transportadora_id' => $data['transportadora_id'] ?? null,
            ]);
        });
    }
}
