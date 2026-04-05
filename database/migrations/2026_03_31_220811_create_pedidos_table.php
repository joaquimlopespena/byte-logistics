<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('descricao')->nullable();
            $table->string('cliente_nome', 150);
            $table->string('produto', 150);
            $table->decimal('preco', 15, 2);
            $table->unsignedInteger('quantidade');
            $table->decimal('total', 15, 2);
            $table->foreignId('transportadora_id')
                ->nullable()
                ->constrained('transportadoras')
                ->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            $table->index('created_at');
            $table->index(['cliente_nome', 'created_at']);
            $table->index('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
