<?php

namespace App\Service;

use App\Models\Pedido;

class FiltroPedidoService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function filtrarPedidos(array $data)
    {
        $query = Pedido::query();

        if (isset($data['search'])) {
            $query->where('cliente_nome', 'like', '%' . $data['search'] . '%')
                ->orWhere('produto', 'like', '%' . $data['search'] . '%')
                ->orWhere('id', $data['search']);
        }

        return $query
            ->orderBy('created_at', 'desc')
            ->with('transportadora:id,nome');
    }
}
