<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiculosTable extends Migration
{
    public function up()
    {
        Schema::create('tb_vehiculos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_lugar'); // Relación con lugares

            // Campos básicos del vehículo
            $table->string('eco')->unique(); // No. ECO
            $table->string('placas')->nullable(); // Placas
            $table->string('marca')->nullable(); // Marca
            $table->string('anio')->nullable(); // Año
            $table->integer('kilometraje')->default(0); // KM actual
            $table->string('conductor_habitual')->nullable(); // Nombre del Conductor

            // Campos adicionales
            $table->string('modelo')->nullable();
            $table->string('color')->nullable();
            $table->enum('estatus', ['activo', 'inactivo', 'mantenimiento'])->default('activo');

            $table->timestamps();

            // Relación con lugares
            $table->foreign('id_lugar')
                ->references('id_lugar')
                ->on('tb_lugares')
                ->onDelete('cascade');

            // Índices
            $table->index(['id_lugar', 'estatus']);
            $table->index('eco');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tb_vehiculos');
    }
}