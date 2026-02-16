<?php

use App\Enums\Etapas;
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
        Schema::create('cursos', function (Blueprint $table) {
            $table->id();
            $table->enum('etapas', Etapas::values()); // Ejemplo: 'ESO', 'Bachillerato', 'FP'
            $table->string('nivel'); // Ejemplo: '1', '2', '3', '4'
            $table->string('letra'); // Ejemplo: 'A', 'B', 'C'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cursos');
    }
};
