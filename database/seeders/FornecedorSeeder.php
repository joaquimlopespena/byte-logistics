<?php

namespace Database\Seeders;

use App\Models\Transportadora;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FornecedorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Transportadora::factory()->count(10)->create([
            'nome' => fake()->company,
            'cnpj' => fake()->cnpj,
            'cep' => fake()->postcode,
            'logradouro' => fake()->streetName,
            'numero' => fake()->buildingNumber,
            'complemento' => fake()->secondaryAddress,
            'bairro' => fake()->neighborhood,
            'cidade' => fake()->city,
            'uf' => fake()->state,
        ]);
    }
}
