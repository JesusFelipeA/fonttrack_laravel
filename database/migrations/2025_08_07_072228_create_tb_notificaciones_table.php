<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_notificaciones', function (Blueprint $table) {
            $table->bigIncrements('id_notificacion'); // Clave primaria
            $table->unsignedBigInteger('id_lugar'); // Relación con lugares

            // ===== DATOS DEL VEHÍCULO =====
            $table->string('eco')->nullable();
            $table->string('placas')->nullable();
            $table->string('marca')->nullable();
            $table->string('anio')->nullable();  // Año del vehículo
            $table->string('km')->nullable();
            $table->date('fecha')->nullable();
            $table->string('nombre_conductor')->nullable();

            // ===== DESCRIPCIÓN DEL PROBLEMA =====
            $table->text('descripcion')->nullable();
            $table->text('observaciones')->nullable();

            // ===== USUARIO QUE REPORTA =====
            $table->unsignedBigInteger('usuario_reporta_id'); // ID del usuario que reporta
            $table->string('nombre_usuario_reporta'); // Nombre del usuario que reporta
            $table->string('correo_usuario_reporta'); // Correo del usuario que reporta

            // ===== MATERIALES =====
            $table->string('material')->nullable(); // Resumen de materiales
            $table->integer('cantidad')->default(0); // Cantidad total descontada
            $table->text('materials')->nullable(); // JSON original de materiales

            // ===== DATOS ADMINISTRATIVOS =====
            $table->string('correo_destino')->nullable(); // Para envío del reporte
            
            // ===== ESTADO DE LA NOTIFICACIÓN =====
            $table->enum('estado', ['pendiente', 'aprobada', 'rechazada'])->default('pendiente');
            
            // ===== DATOS DE APROBACIÓN (Se llenan cuando el admin aprueba) =====
            $table->unsignedBigInteger('usuario_aprueba_id')->nullable(); // ID del admin que aprueba
            $table->string('nombre_usuario_aprueba')->nullable(); // Nombre del admin que aprueba
            $table->string('correo_usuario_aprueba')->nullable(); // Correo del admin que aprueba
            $table->string('autorizado_por')->nullable(); // Firma del admin (usuario que autoriza)
            $table->string('reviso_por')->nullable(); // Contraseña/firma del que revisó
            $table->timestamp('fecha_aprobacion')->nullable(); // Cuándo se aprobó
            $table->text('comentarios_admin')->nullable(); // Comentarios del admin al aprobar/rechazar

            $table->timestamps();

            // ===== LLAVES FORÁNEAS =====
            $table->foreign('id_lugar')
                  ->references('id_lugar')
                  ->on('tb_lugares')
                  ->onDelete('cascade');

            $table->foreign('usuario_reporta_id')
                  ->references('id_usuario')
                  ->on('tb_users')
                  ->onDelete('cascade');

            $table->foreign('usuario_aprueba_id')
                  ->references('id_usuario')
                  ->on('tb_users')
                  ->onDelete('set null');

            // ===== ÍNDICES PARA PERFORMANCE =====
            $table->index('estado');
            $table->index(['estado', 'created_at']);
            $table->index(['id_lugar', 'estado']);
            $table->index('usuario_reporta_id');
            $table->index('fecha');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_notificaciones');
    }
};