<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crea la tabla 'estudiantes' con campos básicos.
     */
    public function up(): void
    {
        Schema::create('estudiantes', function (Blueprint $table) {
            $table->id(); // id autoincremental
            $table->string('nombre', 120); // nombre del estudiante
            $table->string('apellido', 120)->nullable(); // apellido opcional
            $table->string('email')->unique(); // correo único
            $table->string('curso')->nullable(); // curso / modalidad
            $table->date('fecha_nacimiento')->nullable(); // fecha de nacimiento
            $table->string('telefono')->nullable(); // teléfono opcional
            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Elimina la tabla al revertir la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('estudiantes');
    }
};
