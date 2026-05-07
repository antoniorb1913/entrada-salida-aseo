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
            $table->string('etapas');
            $table->string('modalidad')->nullable(); // Esta ya la tendrías así
            $table->string('nivel');
            $table->string('letra')->nullable();    // <--- AÑADE ESTO AQUÍ
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
