<?php

namespace Database\Seeders;

use App\Models\Pedido;
use App\Models\Transportadora;
use Illuminate\Database\Seeder;

class PedidoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pedido::factory()->count(5000)->create([
            'transportadora_id' => fn () => Transportadora::query()->inRandomOrder()->value('id'),
        ]);
    }
}
