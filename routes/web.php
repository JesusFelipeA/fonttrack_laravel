<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\MaterialUsuarioController;
use App\Http\Controllers\LugarController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\FallaController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\VehiculoController;
use App\Models\Lugar;

/* ====================
   PÁGINA DE INICIO
==================== */
Route::get('/', function () {
   return view('welcome');
});

/* ============================
   AUTENTICACIÓN Y SESIÓN
============================ */
Route::get('/login', function () {
   $lugares = Lugar::all();
   return view('login', compact('lugares'));
})->name('login.view');

Route::post('/login', [UsuarioController::class, 'login'])->name('login');
Route::post('/logout', [UsuarioController::class, 'logout'])->name('logout');

/* ============================
   DASHBOARD (solo admins)
============================ */
Route::middleware(['auth', 'role:admin'])->group(function () {
   Route::get('/dashboard', function () {
      return view('dashboard');
   })->name('dashboard');
});

/* ====================
   CRUD: USUARIOS
==================== */
Route::get('/users', [UsuarioController::class, 'index'])->name('users');
Route::get('/users/{id}', [UsuarioController::class, 'show'])->name('user_detail');
Route::post('/register_user', [UsuarioController::class, 'store'])->name('register_user');
Route::get('/edit_user/{id}', [UsuarioController::class, 'edit'])->name('edit_user');
Route::put('/update_user/{id}', [UsuarioController::class, 'update'])->name('update_user');
Route::delete('/delete_user/{id}', [UsuarioController::class, 'destroy'])->name('delete_user');

/* ===========================================
   CRUD vía MODAL (AJAX): USUARIOS
=========================================== */
Route::prefix('modal')->group(function () {
   Route::get('/user/{id}', [UsuarioController::class, 'show'])->name('modal_show_user');
   Route::get('/edit_user/{id}', [UsuarioController::class, 'edit'])->name('modal_edit_user');
   Route::post('/register_user', [UsuarioController::class, 'store'])->name('modal_register_user');
   Route::put('/update_user/{id}', [UsuarioController::class, 'update'])->name('modal_update_user');
   Route::delete('/delete_user/{id}', [UsuarioController::class, 'destroy'])->name('modal_delete_user');
});

/* ===============================================
   MATERIALES - VISTA ADMINISTRADORES
=============================================== */
Route::prefix('materials')->name('materials.')->group(function () {
   Route::get('/', [MaterialController::class, 'index'])->name('index');
   Route::get('/export', [MaterialController::class, 'export'])->name('export');
   Route::get('/template', [MaterialController::class, 'downloadTemplate'])->name('template');
   Route::post('/', [MaterialController::class, 'store'])->name('store');
   Route::get('/{id}', [MaterialController::class, 'show'])->name('show');
   Route::get('/{id}/edit', [MaterialController::class, 'edit'])->name('edit');
   Route::put('/{id}', [MaterialController::class, 'update'])->name('update');
   Route::delete('/{id}', [MaterialController::class, 'destroy'])->name('destroy');
   Route::post('/{id}/aumentar', [MaterialController::class, 'aumentarExistencia'])->name('aumentar');
   Route::post('/import', [MaterialController::class, 'importCardex'])->name('import');

   // Reportes de falla
   Route::post('/reporte-falla', [MaterialController::class, 'crearReporteFalla'])->name('reporte-falla');
   Route::get('/pdf/falla/{id}', [MaterialController::class, 'mostrarPDFFalla'])->name('pdf.falla');
   
   // Vehículos por lugar
   Route::get('/vehiculos-lugar/{id_lugar}', [MaterialController::class, 'getVehiculosPorLugar'])->name('vehiculos.lugar');
   Route::get('/vehiculo-eco/{eco}', [MaterialController::class, 'getVehiculoPorEco'])->name('vehiculo.eco');
});

/* Rutas sin prefijo para compatibilidad */
Route::get('/materials', [MaterialController::class, 'index'])->name('materials');
Route::get('/edit_material/{id}', [MaterialController::class, 'edit']);

/* ================================================
   MATERIALES - VISTA USUARIOS NORMALES
================================================ */
Route::middleware(['auth'])->group(function () {
   Route::get('/materiales', [MaterialUsuarioController::class, 'index'])->name('materiales.index');
   Route::get('/materiales/search', [MaterialUsuarioController::class, 'searchMaterials'])->name('materiales.search');
   Route::get('/materiales/export', [MaterialUsuarioController::class, 'export'])->name('materiales.export');
   Route::get('/materiales/{id}', [MaterialUsuarioController::class, 'show'])->name('materiales.show');

   // Perfil de usuario
   Route::post('/usuario/update-name', [MaterialUsuarioController::class, 'updateName'])->name('usuario.update-name');
   Route::post('/usuario/update-password', [MaterialUsuarioController::class, 'updatePassword'])->name('usuario.update-password');
   Route::post('/usuario/update-photo', [MaterialUsuarioController::class, 'updatePhoto'])->name('usuario.update-photo');
});

/* ====================
   CRUD: LUGARES
==================== */
Route::prefix('lugares')->name('lugares.')->group(function () {
   Route::get('/', [LugarController::class, 'index'])->name('index');
   Route::get('/create', [LugarController::class, 'create'])->name('create');
   Route::post('/', [LugarController::class, 'store'])->name('store');
   Route::get('/{id_lugar}', [LugarController::class, 'show'])->name('show');
   Route::get('/{id_lugar}/edit', [LugarController::class, 'edit'])->name('edit');
   Route::put('/{id_lugar}', [LugarController::class, 'update'])->name('update');
   Route::delete('/{id_lugar}', [LugarController::class, 'destroy'])->name('destroy');
   
   // ✅ USUARIOS POR LUGAR - CORREGIDO
   Route::get('/{id}/usuarios', [MaterialUsuarioController::class, 'getUsersByPlace'])->middleware('auth')->name('usuarios');
});

/* =========================
   REPORTE DE FALLOS (CEDIS)
========================= */
Route::get('/reporte', function () {
   return view('reporte_fallo');
})->name('reporte.form');

Route::post('/reporte', [ReporteController::class, 'enviar'])->name('reporte.enviar');

/* =========================
   SISTEMA DE FALLAS
========================= */
Route::prefix('fallas')->name('fallas.')->group(function () {
   Route::get('/', [FallaController::class, 'index'])->name('index');
   Route::get('/{id}', [FallaController::class, 'show'])->name('show');
   Route::post('/', [FallaController::class, 'store'])->name('store');
   Route::post('/enviar/{id}', [FallaController::class, 'enviar'])->name('enviar');
   Route::get('/pdf/{id}', [FallaController::class, 'pdf'])->name('pdf');
   Route::get('/materiales', [FallaController::class, 'getAllMaterials'])->name('materiales');
   Route::get('/search-materials', [FallaController::class, 'searchMaterials'])->name('search-materials');
});

/* =========================
   RUTAS ESPECIALES
========================= */
Route::get('/reportes', [FallaController::class, 'showReportes'])->name('reportes.index');
Route::post('/verificar-password', [FallaController::class, 'verificarPassword'])->name('verificar-password');
Route::get('/usuarios/admin', [FallaController::class, 'getUsuariosAdmin']);
Route::post('/verificar-password-usuario', [FallaController::class, 'verificarPasswordUsuario']);

/* =========================
   NOTIFICACIONES
========================= */
Route::middleware(['auth'])->group(function () {
   Route::post('/notificaciones', [NotificacionController::class, 'store'])->name('notificaciones.store');
   Route::get('/notificaciones/pendientes', [NotificacionController::class, 'getPendientes'])->name('notificaciones.pendientes');
   Route::get('/notificaciones/contador', [NotificacionController::class, 'getContador'])->name('notificaciones.contador');
   Route::get('/notificaciones/{id}', [NotificacionController::class, 'show'])->name('notificaciones.show');
   Route::post('/notificaciones/{id}/aprobar', [NotificacionController::class, 'aprobar'])->name('notificaciones.aprobar');
   Route::post('/notificaciones/{id}/rechazar', [NotificacionController::class, 'rechazar'])->name('notificaciones.rechazar');
   Route::get('/notificaciones/rechazadas/lista', [NotificacionController::class, 'getRechazadas'])->name('notificaciones.rechazadas');
   Route::delete('/notificaciones/rechazadas/limpiar', [NotificacionController::class, 'limpiarRechazadas'])->name('notificaciones.limpiar');
});

/* ============================
   ✅ VEHÍCULOS - CORREGIDO
============================ */
Route::middleware(['auth'])->group(function () {
   // ✅ RUTAS ESPECÍFICAS PRIMERO (antes del resource)
   Route::get('/vehiculos/search', [VehiculoController::class, 'search'])->name('vehiculos.search');
   Route::post('/vehiculos/import-excel', [VehiculoController::class, 'importExcel'])->name('vehiculos.import-excel');
   Route::get('/vehiculos/{id}/details', [MaterialUsuarioController::class, 'getVehicleById'])->name('vehiculos.details');
   
   // ✅ RESOURCE AL FINAL (para evitar conflictos)
   Route::resource('vehiculos', VehiculoController::class);
});