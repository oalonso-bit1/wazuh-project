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
        /*Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });*/
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id(); 
            $table->string('username')->unique(); // Nombre de usuario único
            $table->string('password');           // Contraseña (la guardaremos en texto para tu prueba o encriptada)
            $table->string('api_key');            // La clave de Wazuh
            $table->enum('role', ['admin', 'agente'])->default('agente'); // Nivel de poder
            $table->timestamps();                 // Fecha de creación/actualización
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
