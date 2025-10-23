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
        Schema::table('tb_fallas', function (Blueprint $table) {
            // Agregar los 3 campos faltantes después de correo_usuario_reporta
            $table->unsignedBigInteger('usuario_revisa_id')->nullable()->after('correo_usuario_reporta');
            $table->string('nombre_usuario_revisa')->nullable()->after('usuario_revisa_id');
            $table->string('correo_usuario_revisa')->nullable()->after('nombre_usuario_revisa');
            
            // Opcional: Agregar índice para mejor rendimiento
            $table->index('usuario_revisa_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_fallas', function (Blueprint $table) {
            // Eliminar índice primero
            $table->dropIndex(['usuario_revisa_id']);
            
            // Eliminar columnas
            $table->dropColumn([
                'usuario_revisa_id',
                'nombre_usuario_revisa',
                'correo_usuario_revisa'
            ]);
        });
    }
};