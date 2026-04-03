<?php

namespace App\Http\Resources;

use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Pedido */
class PedidoResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'descricao' => $this->descricao,
            'cliente_nome' => $this->cliente_nome,
            'produto' => $this->produto,
            'preco' => (float) $this->preco,
            'quantidade' => $this->quantidade,
            'total' => (float) $this->total,
            'transportadora_id' => $this->transportadora_id,
            'transportadora' => $this->whenLoaded('transportadora', function () {
                return [
                    'id' => $this->transportadora->id,
                    'nome' => $this->transportadora->nome,
                ];
            }),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
