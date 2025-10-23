<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Lugar;
use App\Models\Usuarios;
use App\Models\Vehiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class MaterialUsuarioController extends Controller
{
    /**
     * Vista principal de materiales para usuarios
     */
    public function index(Request $request)
    {
        try {
            $lugares = Lugar::all();
            $query = $request->input('query');
            
            // Obtener usuario autenticado
            $user = Auth::user();
            $userLugar = $user->id_lugar ?? null;
            
            Log::info('🔍 MaterialUsuarioController@index', [
                'user_id' => $user->id_usuario ?? 'null',
                'user_lugar' => $userLugar,
                'query' => $query
            ]);
            
            // Construir query de materiales
            $materialesQuery = Material::query();
            
            // Filtrar por lugar del usuario si tiene lugar asignado
            if ($userLugar) {
                $materialesQuery->where('id_lugar', $userLugar);
            }
            
            // Aplicar filtros de búsqueda si existen
            $materiales = $materialesQuery->when($query, function ($q) use ($query) {
                $q->where(function ($subquery) use ($query) {
                    $subquery->where('clave_material', 'like', '%' . $query . '%')
                        ->orWhere('descripcion', 'like', '%' . $query . '%')
                        ->orWhere('generico', 'like', '%' . $query . '%')
                        ->orWhere('clasificacion', 'like', '%' . $query . '%');
                });
            })->paginate(10);

            return view('index_materiales_simple', compact('materiales', 'lugares'));
            
        } catch (\Exception $e) {
            Log::error('Error en MaterialUsuarioController@index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar materiales');
        }
    }

    /**
     * ✅ BÚSQUEDA DE MATERIALES PARA EL MODAL - OPTIMIZADA
     */
    public function searchMaterials(Request $request)
    {
        try {
            Log::info('🔍 searchMaterials iniciado', [
                'request_data' => $request->all(),
                'user_authenticated' => Auth::check(),
                'user_id' => Auth::check() ? Auth::user()->id_usuario : null
            ]);

            $query = trim($request->input('q', ''));
            
            // Validar que hay un query válido
            if (empty($query)) {
                Log::info('❌ Query vacío en searchMaterials');
                return response()->json([]);
            }
            
            if (strlen($query) < 2) {
                Log::info('❌ Query muy corto en searchMaterials: ' . $query);
                return response()->json([]);
            }
            
            // Verificar autenticación
            if (!Auth::check()) {
                Log::error('❌ Usuario no autenticado en searchMaterials');
                return response()->json(['error' => 'Usuario no autenticado'], 401);
            }
            
            $user = Auth::user();
            $userLugar = $user->id_lugar ?? null;
            
            Log::info('👤 Usuario en searchMaterials', [
                'user_id' => $user->id_usuario,
                'user_name' => $user->nombre,
                'user_lugar' => $userLugar
            ]);
            
            // Construir query base
            $materialesQuery = Material::query();
            
            // Filtrar por lugar del usuario si existe
            if ($userLugar) {
                $materialesQuery->where('id_lugar', $userLugar);
                Log::info('🏢 Filtrando por lugar en searchMaterials: ' . $userLugar);
            } else {
                Log::warning('⚠️ Usuario sin lugar asignado en searchMaterials, mostrando todos los materiales');
            }
            
            // ✅ BÚSQUEDA AMPLIADA: ID, Clave, Descripción, Genérico, Clasificación
            $materiales = $materialesQuery
                ->where(function($q) use ($query) {
                    $q->where('id_material', 'like', '%' . $query . '%')
                      ->orWhere('clave_material', 'like', '%' . $query . '%')
                      ->orWhere('descripcion', 'like', '%' . $query . '%')
                      ->orWhere('generico', 'like', '%' . $query . '%')
                      ->orWhere('clasificacion', 'like', '%' . $query . '%');
                })
                ->where('existencia', '>', 0) // Solo materiales con existencia
                ->select([
                    'id_material', 
                    'clave_material', 
                    'descripcion', 
                    'generico', 
                    'clasificacion', 
                    'existencia',
                    'costo_promedio'
                ])
                ->orderBy('clave_material')
                ->limit(15)
                ->get();

            Log::info('📊 Resultados de searchMaterials', [
                'query' => $query,
                'user_lugar' => $userLugar,
                'total_resultados' => $materiales->count(),
                'materiales_encontrados' => $materiales->pluck('clave_material')->toArray()
            ]);

            return response()->json($materiales);
            
        } catch (\Exception $e) {
            Log::error('❌ Error en searchMaterials', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'error' => 'Error en la búsqueda de materiales',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * ✅ BÚSQUEDA DE VEHÍCULOS PARA EL MODAL - NUEVA FUNCIÓN
     */
    public function searchVehicles(Request $request)
    {
        try {
            Log::info('🚗 searchVehicles iniciado', [
                'request_data' => $request->all(),
                'user_authenticated' => Auth::check(),
                'user_id' => Auth::check() ? Auth::user()->id_usuario : null
            ]);

            $query = trim($request->input('q', ''));
            
            // Validar que hay un query válido
            if (empty($query)) {
                Log::info('❌ Query vacío en searchVehicles');
                return response()->json([]);
            }
            
            if (strlen($query) < 2) {
                Log::info('❌ Query muy corto en searchVehicles: ' . $query);
                return response()->json([]);
            }
            
            // Verificar autenticación
            if (!Auth::check()) {
                Log::error('❌ Usuario no autenticado en searchVehicles');
                return response()->json(['error' => 'Usuario no autenticado'], 401);
            }
            
            $user = Auth::user();
            $userLugar = $user->id_lugar ?? null;
            
            Log::info('👤 Usuario en searchVehicles', [
                'user_id' => $user->id_usuario,
                'user_name' => $user->nombre,
                'user_lugar' => $userLugar
            ]);
            
            // Construir query base
            $vehiculosQuery = Vehiculo::query();
            
            // Filtrar por lugar del usuario si existe
            if ($userLugar) {
                $vehiculosQuery->where('id_lugar', $userLugar);
                Log::info('🏢 Filtrando vehículos por lugar: ' . $userLugar);
            } else {
                Log::warning('⚠️ Usuario sin lugar asignado en searchVehicles, mostrando todos los vehículos');
            }
            
            // ✅ BÚSQUEDA AMPLIADA: ECO, Placas, Marca, Modelo, Conductor
            $vehiculos = $vehiculosQuery
                ->where(function($q) use ($query) {
                    $q->where('eco', 'like', '%' . $query . '%')
                      ->orWhere('placas', 'like', '%' . $query . '%')
                      ->orWhere('marca', 'like', '%' . $query . '%')
                      ->orWhere('modelo', 'like', '%' . $query . '%')
                      ->orWhere('conductor_habitual', 'like', '%' . $query . '%');
                })
                ->where('estatus', 'activo') // Solo vehículos activos
                ->select([
                    'id', 
                    'eco', 
                    'placas', 
                    'marca', 
                    'modelo',
                    'anio',
                    'kilometraje',
                    'conductor_habitual',
                    'estatus'
                ])
                ->orderBy('eco')
                ->limit(10)
                ->get();

            Log::info('📊 Resultados de searchVehicles', [
                'query' => $query,
                'user_lugar' => $userLugar,
                'total_resultados' => $vehiculos->count(),
                'vehiculos_encontrados' => $vehiculos->pluck('eco')->toArray()
            ]);

            return response()->json($vehiculos);
            
        } catch (\Exception $e) {
            Log::error('❌ Error en searchVehicles', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'error' => 'Error en la búsqueda de vehículos',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostrar material específico
     */
    public function show($id)
    {
        try {
            if (!is_numeric($id)) {
                return abort(404);
            }

            $user = Auth::user();
            $userLugar = $user->id_lugar ?? null;
            
            $materialQuery = Material::where('id_material', $id);
            
            if ($userLugar) {
                $materialQuery->where('id_lugar', $userLugar);
            }
            
            $material = $materialQuery->first();
                               
            if (!$material) {
                return response()->json(['error' => 'Material no encontrado'], 404);
            }
            
            return response()->json(['data' => $material]);
            
        } catch (\Exception $e) {
            Log::error('Error al mostrar material: ' . $e->getMessage());
            return response()->json(['error' => 'Error al cargar material'], 500);
        }
    }

    /**
     * Exportar materiales
     */
    public function export()
    {
        try {
            $user = Auth::user();
            $userLugar = $user->id_lugar ?? null;
            
            $materialesQuery = Material::where('existencia', '>', 0);
            
            if ($userLugar) {
                $materialesQuery->where('id_lugar', $userLugar);
            }
            
            $materiales = $materialesQuery->orderBy('clave_material')->get();

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Headers
            $sheet->fromArray([
                ['ID Material', 'Clave Material', 'Descripción', 'Genérico', 'Clasificación', 'Existencia', 'Costo Promedio', 'Total ($)']
            ]);

            // Data
            foreach ($materiales as $index => $material) {
                $sheet->fromArray([
                    $material->id_material,
                    $material->clave_material,
                    $material->descripcion,
                    $material->generico,
                    $material->clasificacion,
                    $material->existencia,
                    number_format($material->costo_promedio, 2),
                    number_format($material->existencia * $material->costo_promedio, 2),
                ], null, 'A' . ($index + 2));
            }

            // Styling
            $sheet->getStyle('A1:H1')->getFont()->setBold(true);
            $sheet->getStyle('A1:H1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
            $sheet->getStyle('A1:H1')->getFill()->getStartColor()->setARGB('FFE38B5B');

            // Auto-size columns
            foreach (range('A', 'H') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $writer = new Xlsx($spreadsheet);
            $fileName = 'materiales_existencia_' . now()->format('Y-m-d_H-i') . '.xlsx';
            $tempFile = tempnam(sys_get_temp_dir(), $fileName);
            $writer->save($tempFile);

            return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            Log::error('Error en exportación: ' . $e->getMessage());
            return back()->with('error', 'Error al exportar materiales');
        }
    }

    /**
     * ✅ OBTENER USUARIOS DEL LUGAR - OPTIMIZADO
     */
   public function getUsersByPlace($idLugar)
{
    try {
        Log::info('🏢 getUsersByPlace llamado', ['lugar_id' => $idLugar]);
        
        // Validar que el ID del lugar es numérico
        if (!is_numeric($idLugar)) {
            Log::error('❌ ID de lugar no válido: ' . $idLugar);
            return response()->json(['error' => 'ID de lugar no válido'], 400);
        }
        
        // Verificar que el usuario autenticado puede acceder a este lugar
        $user = Auth::user();
        if (!$user) {
            Log::error('❌ Usuario no autenticado en getUsersByPlace');
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }
        
        // Si no es admin (tipo_usuario != 1), verificar que el lugar pertenece al usuario
        if ($user->tipo_usuario != 1 && $user->id_lugar != $idLugar) {
            Log::warning('⚠️ Usuario intenta acceder a usuarios de otro lugar', [
                'user_id' => $user->id_usuario,
                'user_lugar' => $user->id_lugar,
                'lugar_solicitado' => $idLugar
            ]);
            return response()->json(['error' => 'No autorizado para acceder a este lugar'], 403);
        }
        
        // Verificar que el lugar existe
        $lugar = Lugar::find($idLugar);
        if (!$lugar) {
            Log::error('❌ Lugar no encontrado: ' . $idLugar);
            return response()->json(['error' => 'Lugar no encontrado'], 404);
        }
        
        // ✅ OBTENER USUARIOS DEL LUGAR (estructura corregida)
        $usuarios = Usuarios::where('id_lugar', $idLugar)
                          ->select([
                              'id_usuario as id',  // ✅ Campo correcto
                              'nombre as name',    // ✅ Campo correcto
                              'correo as email',   // ✅ Campo correcto
                              'tipo_usuario'       // ✅ Campo correcto
                          ])
                          ->orderBy('nombre')
                          ->get();

        Log::info('👥 Usuarios encontrados', [
            'lugar_id' => $idLugar,
            'lugar_nombre' => $lugar->nombre,
            'count' => $usuarios->count(),
            'usuarios' => $usuarios->pluck('name')->toArray()
        ]);

        return response()->json([
            'success' => true,
            'usuarios' => $usuarios,
            'lugar' => [
                'id' => $lugar->id_lugar,
                'nombre' => $lugar->nombre
            ]
        ]);
        
    } catch (\Exception $e) {
        Log::error('❌ Error al obtener usuarios por lugar', [
            'lugar_id' => $idLugar,
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'error' => 'Error al cargar usuarios del lugar',
            'message' => $e->getMessage()
        ], 500);
    }
}

    /**
     * ✅ ACTUALIZAR NOMBRE DEL USUARIO
     */
    public function updateName(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:255|min:2'
            ]);

            $user = Auth::user();
            $oldName = $user->nombre;
            $user->nombre = trim($request->nombre);
            $user->save();

            Log::info('👤 Nombre actualizado', [
                'user_id' => $user->id_usuario,
                'nombre_anterior' => $oldName,
                'nombre_nuevo' => $user->nombre
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Nombre actualizado correctamente',
                'data' => [
                    'nombre' => $user->nombre
                ]
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Datos no válidos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('❌ Error al actualizar nombre', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false, 
                'message' => 'Error al actualizar nombre'
            ], 500);
        }
    }

    /**
     * ✅ ACTUALIZAR CONTRASEÑA DEL USUARIO
     */
    public function updatePassword(Request $request)
    {
        try {
            $request->validate([
                'current_password' => 'required',
                'password' => 'required|min:6|confirmed'
            ]);

            $user = Auth::user();

            // Verificar contraseña actual
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'La contraseña actual es incorrecta'
                ], 400);
            }

            // Verificar que la nueva contraseña sea diferente
            if (Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'La nueva contraseña debe ser diferente a la actual'
                ], 400);
            }

            $user->password = Hash::make($request->password);
            $user->save();

            Log::info('🔒 Contraseña actualizada', [
                'user_id' => $user->id_usuario,
                'timestamp' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Contraseña actualizada correctamente'
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Datos no válidos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('❌ Error al actualizar contraseña', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false, 
                'message' => 'Error al actualizar contraseña'
            ], 500);
        }
    }

    /**
     * ✅ ACTUALIZAR FOTO DEL USUARIO
     */
    public function updatePhoto(Request $request)
    {
        try {
            $request->validate([
                'foto_usuario' => 'required|image|mimes:jpeg,png,jpg|max:2048' // 2MB max
            ]);

            $user = Auth::user();
            $oldPhoto = $user->foto_usuario;

            if ($request->hasFile('foto_usuario')) {
                // Eliminar foto anterior si existe
                if ($oldPhoto && file_exists(public_path('img/' . $oldPhoto))) {
                    unlink(public_path('img/' . $oldPhoto));
                    Log::info('📷 Foto anterior eliminada: ' . $oldPhoto);
                }

                // Subir nueva foto
                $file = $request->file('foto_usuario');
                $fileName = 'user_' . $user->id_usuario . '_' . time() . '.' . $file->getClientOriginalExtension();
                
                // Asegurar que el directorio existe
                if (!is_dir(public_path('img'))) {
                    mkdir(public_path('img'), 0755, true);
                }
                
                $file->move(public_path('img'), $fileName);
                
                $user->foto_usuario = $fileName;
                $user->save();

                Log::info('📷 Foto actualizada', [
                    'user_id' => $user->id_usuario,
                    'foto_anterior' => $oldPhoto,
                    'foto_nueva' => $fileName
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Imagen actualizada correctamente',
                    'data' => [
                        'foto_url' => asset('img/' . $fileName)
                    ]
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No se pudo subir la imagen'
            ], 400);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Archivo no válido',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('❌ Error al actualizar foto', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false, 
                'message' => 'Error al actualizar foto'
            ], 500);
        }
    }

    /**
     * ✅ OBTENER VEHÍCULO POR ID - NUEVA FUNCIÓN
     */
    public function getVehicleById($id)
    {
        try {
            Log::info('🚗 getVehicleById llamado', ['vehicle_id' => $id]);
            
            if (!is_numeric($id)) {
                return response()->json(['error' => 'ID de vehículo no válido'], 400);
            }
            
            $user = Auth::user();
            $vehiculoQuery = Vehiculo::where('id', $id);
            
            // Filtrar por lugar del usuario si no es admin
            if ($user->tipo_usuario != 1 && $user->id_lugar) {
                $vehiculoQuery->where('id_lugar', $user->id_lugar);
            }
            
            $vehiculo = $vehiculoQuery->first();
            
            if (!$vehiculo) {
                return response()->json(['error' => 'Vehículo no encontrado'], 404);
            }
            
            Log::info('✅ Vehículo encontrado', [
                'vehicle_id' => $id,
                'eco' => $vehiculo->eco
            ]);
            
            return response()->json(['data' => $vehiculo]);
            
        } catch (\Exception $e) {
            Log::error('❌ Error al obtener vehículo', [
                'vehicle_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json(['error' => 'Error al cargar vehículo'], 500);
        }
    }

/**
 * ✅ BÚSQUEDA AJAX DE VEHÍCULOS - FUNCIONAL
 */
public function search(Request $request)
{
    try {
        $query = trim($request->input('q', ''));
        
        Log::info('🚗 VehiculoController@search iniciado', [
            'query' => $query,
            'user_id' => Auth::id()
        ]);
        
        // Validar que hay un query válido
        if (empty($query) || strlen($query) < 2) {
            return response()->json([]);
        }
        
        // Verificar autenticación
        if (!Auth::check()) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }
        
        $user = Auth::user();
        $userLugar = $user->id_lugar ?? null;
        
        // Construir query base
        $vehiculosQuery = Vehiculo::query();
        
        // Filtrar por lugar del usuario si no es admin
        if ($user->tipo_usuario != 1 && $userLugar) {
            $vehiculosQuery->where('id_lugar', $userLugar);
        }
        
        // ✅ BÚSQUEDA EN MÚLTIPLES CAMPOS
        $vehiculos = $vehiculosQuery
            ->where(function($q) use ($query) {
                $q->where('eco', 'like', '%' . $query . '%')
                  ->orWhere('placas', 'like', '%' . $query . '%')
                  ->orWhere('marca', 'like', '%' . $query . '%')
                  ->orWhere('modelo', 'like', '%' . $query . '%')
                  ->orWhere('conductor_habitual', 'like', '%' . $query . '%');
            })
            ->whereIn('estatus', ['activo', 'mantenimiento'])
            ->select([
                'id', 
                'eco', 
                'placas', 
                'marca', 
                'modelo',
                'anio',
                'kilometraje',
                'conductor_habitual',
                'estatus'
            ])
            ->orderBy('eco')
            ->limit(10)
            ->get();

        Log::info('📊 Resultados de vehicle search', [
            'query' => $query,
            'total_resultados' => $vehiculos->count()
        ]);

        return response()->json($vehiculos);
        
    } catch (\Exception $e) {
        Log::error('❌ Error en búsqueda de vehículos', [
            'error' => $e->getMessage(),
            'query' => $request->input('q'),
            'user_id' => Auth::id()
        ]);
        
        return response()->json([
            'error' => 'Error en la búsqueda de vehículos'
        ], 500);
    }
}



}