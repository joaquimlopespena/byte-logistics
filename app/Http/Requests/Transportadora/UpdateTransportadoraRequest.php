<?php

namespace App\Http\Requests\Transportadora;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTransportadoraRequest extends FormRequest
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
        $cnpj = $this->input('cnpj');
        $cep = $this->input('cep');

        $this->merge([
            'cnpj' => is_string($cnpj) ? preg_replace('/\D/', '', $cnpj) : $cnpj,
            'cep' => is_string($cep) ? preg_replace('/\D/', '', $cep) : $cep,
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
            'nome' => 'required|string|max:150',
            'cnpj' => 'required|digits:14|unique:transportadoras,cnpj,' . $this->transportadora->id,
            'cep' => 'required|digits:8',
            'uf' => 'required|string|size:2',
            'cidade' => 'required|string|max:100',
            'bairro' => 'required|string|max:100',
            'logradouro' => 'required|string|max:250',
            'numero' => 'required|string|max:20',
            'complemento' => 'nullable|string|max:250',
            'transportadora_id' => 'nullable|exists:transportadoras,id',
        ];
    }
}
