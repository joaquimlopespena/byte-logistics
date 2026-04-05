<?php

namespace App\Console\Commands\Pedido;

use App\Models\Transportadora;
use App\Service\DashboardStatsService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

#[Signature('pedido:gerar_massa {--count=1000000 : Quantidade de pedidos a inserir} {--chunk=2000 : Linhas por INSERT (ajuste se o banco limitar o pacote)} {--force : Não pedir confirmação}')]
#[Description('Insere pedidos em massa via INSERT em lotes (adequado para ~1M+ registros)')]
class GerarMassa extends Command
{
    public function handle(): int
    {
        $count = max(0, (int) $this->option('count'));
        $chunkSize = max(1, min(5000, (int) $this->option('chunk')));

        if (DB::getDriverName() === 'sqlite' && $chunkSize > 100) {
            $chunkSize = 100;
            $this->components->warn('Driver sqlite: usando no máximo 100 linhas por INSERT (limite de placeholders).');
        }

        if ($count === 0) {
            $this->components->warn('Nada a inserir (--count=0).');

            return self::SUCCESS;
        }

        $transportadoraIds = Transportadora::query()->pluck('id')->all();
        if ($transportadoraIds === []) {
            $this->components->error('Cadastre ao menos uma transportadora antes de gerar pedidos.');

            return self::FAILURE;
        }

        if ($count >= 50_000 && ! $this->option('force')) {
            if (! $this->confirm("Serão inseridos {$count} pedidos. Confirma?", false)) {
                return self::SUCCESS;
            }
        }

        $produtos = $this->produtos();
        $faker = fake();
        $nomePool = [];
        for ($i = 0; $i < 2000; $i++) {
            $nomePool[] = Str::limit($faker->name(), 150, '');
        }
        $descPool = [];
        for ($i = 0; $i < 500; $i++) {
            $descPool[] = Str::limit($faker->sentence(), 500, '');
        }

        $now = now()->format('Y-m-d H:i:s');
        $transportadoraCount = count($transportadoraIds);
        $produtoCount = count($produtos);
        $nomeCount = count($nomePool);
        $descCount = count($descPool);

        $this->info("Inserindo {$count} pedidos em lotes de {$chunkSize}…");

        $inserted = 0;
        $buffer = [];
        $bar = $this->output->createProgressBar((int) ceil($count / $chunkSize));
        $bar->start();

        for ($i = 0; $i < $count; $i++) {
            $preco = round(mt_rand(2_990, 899_999) / 100, 2);
            $quantidade = random_int(1, 15);
            $total = round($preco * $quantidade, 2);
            $descricao = random_int(1, 100) <= 45
                ? $descPool[random_int(0, $descCount - 1)]
                : null;

            $buffer[] = [
                'descricao' => $descricao,
                'cliente_nome' => $nomePool[random_int(0, $nomeCount - 1)],
                'produto' => $produtos[random_int(0, $produtoCount - 1)],
                'preco' => $preco,
                'quantidade' => $quantidade,
                'total' => $total,
                'transportadora_id' => $transportadoraIds[random_int(0, $transportadoraCount - 1)],
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ];

            if (count($buffer) >= $chunkSize) {
                DB::table('pedidos')->insert($buffer);
                $inserted += count($buffer);
                $buffer = [];
                $bar->advance();
            }
        }

        if ($buffer !== []) {
            DB::table('pedidos')->insert($buffer);
            $inserted += count($buffer);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->components->info("Concluído: {$inserted} pedidos inseridos.");
        DashboardStatsService::forgetCache();
        return self::SUCCESS;
    }

    /**
     * @return list<string>
     */
    private function produtos(): array
    {
        $path = database_path('data/produtos.json');
        $json = file_get_contents($path);
        if ($json === false) {
            throw new \RuntimeException("Não foi possível ler o arquivo: {$path}");
        }

        /** @var list<string> $lista */
        $lista = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        return array_map(fn (string $p) => Str::limit($p, 150, ''), $lista);
    }
}
