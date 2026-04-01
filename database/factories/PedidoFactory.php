<?php

namespace Database\Factories;

use App\Models\Pedido;
use App\Models\Transportadora;
use Illuminate\Database\Eloquent\Factories\Factory;
use RuntimeException;

/**
 * @extends Factory<Pedido>
 */
class PedidoFactory extends Factory
{
    protected $model = Pedido::class;

    /** @var list<string>|null */
    protected static ?array $produtos = null;

    /**
     * @return list<string>
     */
    protected static function produtos(): array
    {
        if (static::$produtos !== null) {
            return static::$produtos;
        }

        $path = database_path('data/produtos.json');
        $json = file_get_contents($path);
        if ($json === false) {
            throw new RuntimeException("Não foi possível ler o arquivo: {$path}");
        }

        /** @var list<string> $lista */
        $lista = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        if ($lista === []) {
            throw new RuntimeException('Lista de produtos em produtos.json está vazia.');
        }

        return static::$produtos = $lista;
    }

    public function definition(): array
    {
        $preco = fake()->randomFloat(2, 29.90, 8999.99);
        $quantidade = fake()->numberBetween(1, 15);

        return [
            'descricao' => fake()->optional(0.55)->sentence(),
            'cliente_nome' => fake()->name(),
            'produto' => fake()->randomElement(static::produtos()),
            'preco' => $preco,
            'quantidade' => $quantidade,
            'total' => round($preco * $quantidade, 2),
            'transportadora_id' => Transportadora::factory(),
        ];
    }
}
