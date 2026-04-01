<?php

namespace Database\Factories;

use App\Models\Transportadora;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Transportadora>
 */
class TransportadoraFactory extends Factory
{
    protected $model = Transportadora::class;

    public function definition(): array
    {
        $ufs = ['SP', 'RJ', 'MG', 'RS', 'PR', 'SC', 'BA', 'GO', 'PE', 'CE'];

        return [
            'nome' => fake()->company(),
            'cnpj' => fake()->unique()->numerify('##############'),
            'cep' => fake()->numerify('########'),
            'logradouro' => fake()->streetName(),
            'numero' => (string) fake()->numberBetween(1, 9999),
            'complemento' => fake()->optional(0.35)->secondaryAddress(),
            'bairro' => fake()->words(2, true),
            'cidade' => fake()->city(),
            'uf' => fake()->randomElement($ufs),
        ];
    }
}
