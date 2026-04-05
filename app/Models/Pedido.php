<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pedido extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'descricao',
        'cliente_nome',
        'produto',
        'preco',
        'quantidade',
        'total',
        'transportadora_id',
    ];

    public function transportadora()
    {
        return $this->belongsTo(Transportadora::class);
    }
}
