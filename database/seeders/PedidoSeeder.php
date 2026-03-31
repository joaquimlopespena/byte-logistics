<?php

namespace Database\Seeders;

use App\Models\Pedido;
use App\Models\Transportadora;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PedidoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pedido::factory()->count(10)->create([
            'cliente_nome' => fake()->name,
            'produto' => fake()->word,
            'preco' => fake()->randomFloat(2, 10, 100),
            'quantidade' => fake()->numberBetween(1, 10),
            'total' => fake()->randomFloat(2, 10, 100),
            'transportadora_id' => Transportadora::factory(),
        ]);
    }
}
