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
        Schema::create('transportadoras', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 150);
            $table->string('cnpj', 14)->unique(); // CNPJ como índice único
            $table->string('cep', 8)->index();
            $table->string('logradouro', 250);
            $table->string('numero', 20);
            $table->string('complemento')->nullable();
            $table->string('bairro', 100);
            $table->string('cidade', 100);
            $table->char('uf', 2)->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transportadoras');
    }
};
