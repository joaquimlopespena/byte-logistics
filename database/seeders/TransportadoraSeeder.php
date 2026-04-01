<?php

namespace Database\Seeders;

use App\Models\Transportadora;
use Illuminate\Database\Seeder;

class TransportadoraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Transportadora::factory()->count(10)->create();
    }
}
