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
            // ✅ Agregar los 3 campos faltantes
            $table->unsignedBigInteger('usuario_reporta_id')->nullable()->after('id_lugar');
            $table->string('nombre_usuario_reporta')->nullable()->after('usuario_reporta_id');
            $table->string('correo_usuario_reporta')->nullable()->after('nombre_usuario_reporta');
            
            // ✅ Agregar llave foránea
            $table->foreign('usuario_reporta_id')
                  ->references('id_usuario')
                  ->on('tb_users')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_fallas', function (Blueprint $table) {
            // ✅ Eliminar llave foránea primero
            $table->dropForeign(['usuario_reporta_id']);
            
            // ✅ Eliminar los campos agregados
            $table->dropColumn([
                'usuario_reporta_id', 
                'nombre_usuario_reporta', 
                'correo_usuario_reporta'
            ]);
        });
    }
};