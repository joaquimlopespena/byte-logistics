<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PedidoCollection extends ResourceCollection
{
    public $collects = PedidoResource::class;
}
