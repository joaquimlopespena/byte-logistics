<?php

namespace App\Service;

use App\Models\Transportadora;

class FilterTransportadoraService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function filtrarTransportadoras(array $data)
    {
        $query = Transportadora::query();

        if (!empty($data['search'])) {
            $query->where('nome', 'like', '%' . $data['search'] . '%');
            $cnpj = preg_replace('/\D/', '', (string) ($data['search'] ?? ''));
            if (strlen($cnpj) === 14) {
                $query->orWhere('cnpj', $cnpj);
            }
        }

        return $query->orderBy('created_at', 'desc');
    }
}
