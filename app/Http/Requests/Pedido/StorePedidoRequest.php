<?php

namespace App\Http\Requests\Pedido;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePedidoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $preco = $this->input('preco');
        $quantidade = $this->input('quantidade');
        $total = $this->input('total');

        $this->merge([
            'preco' => is_string($preco) ? str_replace(['.', ','], ['', '.'], $preco) : $preco,
            'total' => is_string($total) ? str_replace(['.', ','], ['', '.'], $total) : $total,
            'quantidade' => is_string($quantidade) ? str_replace(['.', ','], ['', '.'], $quantidade) : $quantidade,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nome_cliente' => 'required|string|max:150',
            'produto' => 'required|string|max:150',
            'descricao' => 'nullable|string|max:255',
            'preco' => 'required',
            'quantidade' => 'required|integer|min:1',
            'total' => 'required',
            'transportadora_id' => 'required|exists:transportadoras,id',
        ];
    }
}
