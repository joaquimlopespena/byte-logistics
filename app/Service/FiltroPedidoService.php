<?php

namespace App\Service;

use App\Models\Pedido;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class FiltroPedidoService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function filtrarPedidos(array $data, ?int $userId = null): Builder
    {
        return $this->aplicarFiltros(Pedido::query(), $data, $userId)
            ->orderBy('created_at', 'desc')
            ->with('transportadora:id,nome');
    }

    /**
     * Aplica os mesmos filtros da listagem sobre um builder (ex.: exportação em fila sem global scope).
     *
     * @param  array<string, mixed>  $data
     */
    public function aplicarFiltros(Builder $query, array $data, ?int $userId = null): Builder
    {
        $search = $data['search'] ?? null;
        if (is_string($search) && $search !== '') {
            $query->where(function (Builder $q) use ($search): void {
                $q->where('cliente_nome', 'like', '%'.$search.'%')
                    ->orWhere('produto', 'like', '%'.$search.'%');
                if (ctype_digit((string) $search)) {
                    $q->orWhere('id', (int) $search);
                }
            });
        }

        $dataInicio = $this->parseDateParam($data['data_inicio'] ?? null);
        $dataFim = $this->parseDateParam($data['data_fim'] ?? null);

        if ($dataInicio !== null && $dataFim !== null) {
            $inicio = $dataInicio->copy()->startOfDay();
            $fim = $dataFim->copy()->endOfDay();
            if ($inicio->lte($fim)) {
                $query->whereBetween('created_at', [$inicio, $fim]);
            }
        } elseif ($dataInicio !== null) {
            $query->where('created_at', '>=', $dataInicio->copy()->startOfDay());
        } elseif ($dataFim !== null) {
            $query->where('created_at', '<=', $dataFim->copy()->endOfDay());
        }

        if ($userId !== null) {
            $query->where('user_id', $userId);
        }

        return $query;
    }

    private function parseDateParam(mixed $value): ?Carbon
    {
        if (! is_string($value) || ! preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return null;
        }

        return Carbon::createFromFormat('Y-m-d', $value)->startOfDay();
    }
}
