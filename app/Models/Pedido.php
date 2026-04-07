<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Pedido extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'descricao',
        'cliente_nome',
        'produto',
        'preco',
        'quantidade',
        'total',
        'transportadora_id',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('user', function (Builder $builder) {
            $user = Auth::user() ?? null;
            if ($user) {
                $builder->where('user_id', $user->id);
            }
        });
    }

    public function transportadora()
    {
        return $this->belongsTo(Transportadora::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
