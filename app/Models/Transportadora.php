<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transportadora extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nome',
        'cnpj',
        'cep',
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'uf',
    ];

    public function getCnpjFormatadoAttribute(): string
    {
        $digits = preg_replace('/\D/', '', (string) ($this->attributes['cnpj'] ?? ''));

        if (strlen($digits) !== 14) {
            return (string) ($this->attributes['cnpj'] ?? '');
        }

        return sprintf(
            '%s.%s.%s/%s-%s',
            substr($digits, 0, 2),
            substr($digits, 2, 3),
            substr($digits, 5, 3),
            substr($digits, 8, 4),
            substr($digits, 12, 2)
        );
    }

    public function getCepFormatadoAttribute(): string
    {
        $digits = preg_replace('/\D/', '', (string) ($this->attributes['cep'] ?? ''));

        if (strlen($digits) !== 8) {
            return (string) ($this->attributes['cep'] ?? '');
        }

        return substr($digits, 0, 5).'-'.substr($digits, 5, 3);
    }
}
