<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePedidoApiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $preco = $this->input('preco');
        $quantidade = $this->input('quantidade');

        $merge = [];
        if (is_string($preco)) {
            $merge['preco'] = str_replace(['.', ','], ['', '.'], $preco);
        }
        if (is_string($quantidade)) {
            $merge['quantidade'] = str_replace(['.', ','], ['', '.'], $quantidade);
        }
        if ($merge !== []) {
            $this->merge($merge);
        }
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'cliente_nome' => 'required|string|max:150',
            'produto' => 'required|string|max:150',
            'descricao' => 'nullable|string',
            'preco' => 'required|numeric|min:0',
            'quantidade' => 'required|integer|min:1',
            'transportadora_id' => 'nullable|exists:transportadoras,id',
        ];
    }
}
