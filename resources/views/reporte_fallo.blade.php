<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Nueva Vista - Reporte de Fallas y Uso de Materiales</title>
    <style>
        /* Reset básico */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #fafafa;
            color: #333;
            padding: 20px;
        }

        /* Contenedor centrado para limitar el ancho y que todo quepa en una hoja */
        .container {
            max-width: 600px;
            margin: 0 auto;
        }

        /* Header: banner con imagen a lo ancho */
        header {
            width: 100%;
            margin-bottom: 20px;
        }

        header img {
            display: block;
            width: 100%;
            height: auto;
            max-height: 150px;
        }

        h1,
        h3 {
            text-align: center;
            margin-bottom: 15px;
        }

        /* Estilos de las tablas: más compactas para encajar en una sola hoja */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            font-size: 10px;
        }

        /* Tabla de datos generales */
        .data-table th {
            background: #eaeaea;
            padding: 6px 8px;
            width: 35%;
            border: 1px solid #ddd;
        }

        .data-table td {
            padding: 6px 8px;
            background: #fff;
            border: 1px solid #ddd;
        }

        /* Tabla para Materiales Utilizados */
        table thead {
            background: #007ACC;
            color: #fff;
        }

        table thead th {
            padding: 6px 8px;
            border: 1px solid #ddd;
        }

        table tbody tr {
            border-bottom: 1px solid #ddd;
        }

        table tbody tr:nth-child(even) {
            background: #f9f9f9;
        }

        table tbody td {
            padding: 6px 8px;
            border: 1px solid #ddd;
        }

        table th,
        table td {
            text-align: left;
        }

        /* Sección de firma */
        .firma {
            margin-top: 30px;
            padding: 10px 20px;
            border-top: 1px solid #ddd;
            font-size: 10px;
        }

        /* Footer con olas */
        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            overflow: hidden;
            pointer-events: none;
        }

        footer img {
            display: block;
            width: 100%;
            height: auto;
            opacity: 0.1;
            max-height: 100px;
        }

        footer::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, rgba(250, 250, 250, 0), rgba(250, 250, 250, 0.05));
            pointer-events: none;
        }

        /* ✅ NUEVOS ESTILOS para materiales */
        .material-summary {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 8px;
            margin-bottom: 10px;
            font-size: 9px;
        }

        .material-summary strong {
            color: #007ACC;
        }

        .material-row {
            border-bottom: 1px solid #ddd;
        }

        .material-row:last-child {
            border-bottom: none;
        }

        .no-materials {
            text-align: center;
            color: #666;
            font-style: italic;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        /* Opcional: estilos para impresión */
        @media print {
            body {
                padding: 10px;
            }

            .container {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            @if(file_exists(public_path('img/completa.png')))
                <img src="{{ public_path('img/completa.png') }}" alt="Banner">
            @else
                <div style="height: 100px; background: #007ACC; color: white; display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: bold;">
                    FONTTRACK - SISTEMA DE REPORTES
                </div>
            @endif
        </header>

        <h1>Reporte de Fallas / Uso de Materiales</h1>

        <!-- Tabla de datos generales -->
        <table class="data-table">
            <tbody>
                <tr>
                    <th>Lugar</th>
                    <td>{{ $data['lugar'] ?? 'No especificado' }}</td>
                </tr>
                <tr>
                    <th>No. ECO</th>
                    <td>{{ $data['eco'] ?? '' }}</td>
                </tr>
                <tr>
                    <th>Placas</th>
                    <td>{{ $data['placas'] ?? '' }}</td>
                </tr>
                <tr>
                    <th>Marca</th>
                    <td>{{ $data['marca'] ?? '' }}</td>
                </tr>
                <tr>
                    <th>Año</th>
                    <td>{{ $data['anio'] ?? '' }}</td>
                </tr>
                <tr>
                    <th>KM</th>
                    <td>{{ $data['km'] ?? '' }}</td>
                </tr>
                <tr>
                    <th>Fecha</th>
                    <td>{{ $data['fecha'] ?? '' }}</td>
                </tr>
                <tr>
                    <th>Nombre del Conductor</th>
                    <td>{{ $data['nombre_conductor'] ?? '' }}</td>
                </tr>
            </tbody>
        </table>

        <h3>Descripción del Servicio / Fallo</h3>
        <p>{{ $data['descripcion'] ?? 'Sin descripción' }}</p>

        <h3>Observaciones Técnicas del Trabajo Realizado</h3>
        <p>{{ $data['observaciones'] ?? 'Sin observaciones' }}</p>

        <h3>Materiales Utilizados</h3>
        
        @php
            // ✅ ESTRATEGIA MULTI-NIVEL para obtener materiales (CORRIGE EL PROBLEMA)
            $materialesFormateados = [];
            
            // ESTRATEGIA 1: Usar el modelo Falla con su método corregido
            if (isset($data['falla']) && method_exists($data['falla'], 'getMaterialesFormateadosAttribute')) {
                $materialesFormateados = $data['falla']->materiales_formateados;
            }
            
            // ESTRATEGIA 2: Procesar materials del array data
            if (empty($materialesFormateados) && isset($data['materials']) && !empty($data['materials'])) {
                $materials = $data['materials'];
                if (is_string($materials)) {
                    $materials = json_decode($materials, true);
                }
                
                if (is_array($materials)) {
                    $materialesFormateados = collect($materials)->map(function ($material) {
                        $materialId = $material['id'] ?? $material['id_material'] ?? $material['material_id'] ?? null;
                        
                        // ✅ PRIORIZAR descripción del array, luego buscar en BD
                        $descripcion = 'Material no especificado';
                        if (!empty($material['descripcion'])) {
                            $descripcion = $material['descripcion'];
                        } elseif ($materialId) {
                            $materialModel = \App\Models\Material::find($materialId);
                            if ($materialModel && $materialModel->descripcion) {
                                $descripcion = $materialModel->descripcion;
                            }
                        } elseif (!empty($material['nombre'])) {
                            $descripcion = $material['nombre'];
                        } elseif (!empty($material['name'])) {
                            $descripcion = $material['name'];
                        }
                        
                        $cantidad = $material['cantidad'] ?? $material['qty'] ?? $material['quantity'] ?? 0;
                        $materialModel = $materialId ? \App\Models\Material::find($materialId) : null;
                        
                        return [
                            'id' => $materialId,
                            'descripcion' => $descripcion,
                            'cantidad' => $cantidad,
                            'clave' => $materialModel?->clave_material ?? 'N/A',
                            'costo_unitario' => $materialModel?->costo_promedio ?? 0,
                            'costo_total' => ($materialModel?->costo_promedio ?? 0) * $cantidad
                        ];
                    })->toArray();
                }
            }
            
            // ESTRATEGIA 3: Usar materials directamente de la falla si existe
            if (empty($materialesFormateados) && isset($data['falla']) && !empty($data['falla']->materials)) {
                $materials = $data['falla']->materials;
                if (is_array($materials)) {
                    $materialesFormateados = collect($materials)->map(function ($material) {
                        $materialId = $material['id'] ?? $material['id_material'] ?? null;
                        
                        $descripcion = 'Material no especificado';
                        if (!empty($material['descripcion'])) {
                            $descripcion = $material['descripcion'];
                        } elseif ($materialId) {
                            $materialModel = \App\Models\Material::find($materialId);
                            if ($materialModel && $materialModel->descripcion) {
                                $descripcion = $materialModel->descripcion;
                            }
                        }
                        
                        $cantidad = $material['cantidad'] ?? 0;
                        $materialModel = $materialId ? \App\Models\Material::find($materialId) : null;
                        
                        return [
                            'descripcion' => $descripcion,
                            'cantidad' => $cantidad,
                            'clave' => $materialModel?->clave_material ?? 'N/A',
                            'costo_unitario' => $materialModel?->costo_promedio ?? 0,
                            'costo_total' => ($materialModel?->costo_promedio ?? 0) * $cantidad
                        ];
                    })->toArray();
                }
            }
            
            // ESTRATEGIA 4: Fallback a campos legacy
            if (empty($materialesFormateados)) {
                $materialLegacy = $data['material'] ?? (isset($data['falla']) ? $data['falla']->material : null);
                $cantidadLegacy = $data['cantidad'] ?? (isset($data['falla']) ? $data['falla']->cantidad : 0);
                
                if (!empty($materialLegacy)) {
                    $materialesFormateados = [
                        [
                            'id' => null,
                            'descripcion' => $materialLegacy,
                            'cantidad' => $cantidadLegacy,
                            'clave' => 'N/A',
                            'costo_unitario' => 0,
                            'costo_total' => 0
                        ]
                    ];
                }
            }
        @endphp
        
        @if(count($materialesFormateados) > 0)
            <!-- ✅ Resumen de materiales -->
            

            <table>
                <thead>
                    <tr>
                        <th>Clave</th>
                        <th>Material</th>
                        <th>Cantidad</th>
                        @if(collect($materialesFormateados)->where('costo_unitario', '>', 0)->count() > 0)
                            <th>Costo Unit.</th>
                            <th>Costo Total</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($materialesFormateados as $material)
                        <tr class="material-row">
                            <td>{{ $material['clave'] ?? 'N/A' }}</td>
                            <td><strong>{{ $material['descripcion'] }}</strong></td>
                            <td>{{ $material['cantidad'] }}</td>
                            @if(collect($materialesFormateados)->where('costo_unitario', '>', 0)->count() > 0)
                                <td>${{ number_format($material['costo_unitario'] ?? 0, 2) }}</td>
                                <td>${{ number_format($material['costo_total'] ?? 0, 2) }}</td>
                            @endif
                        </tr>
                    @endforeach
                    
                    @if(collect($materialesFormateados)->where('costo_unitario', '>', 0)->count() > 0)
                        <tr style="background: #f8f9fa; font-weight: bold;">
                            <td colspan="{{ collect($materialesFormateados)->where('costo_unitario', '>', 0)->count() > 0 ? '3' : '2' }}">TOTAL</td>
                            <td>{{ collect($materialesFormateados)->sum('cantidad') }}</td>
                            @if(collect($materialesFormateados)->where('costo_unitario', '>', 0)->count() > 0)
                                <td>${{ number_format(collect($materialesFormateados)->sum('costo_total'), 2) }}</td>
                            @endif
                        </tr>
                    @endif
                </tbody>
            </table>
        @else
            <div class="no-materials">
                <p><strong>⚠️ No se registraron materiales en este reporte</strong></p>
            </div>
        @endif

        <div class="firma">
            <p><strong>Autorizado por:</strong> {{ $data['autorizado_por'] ?? '' }}</p>
            <p><strong>Revisado por:</strong> {{ $data['reviso_por'] ?? '' }}</p>
            @if(isset($data['fecha_generacion']))
                <p><strong>Fecha de generación:</strong> {{ $data['fecha_generacion'] }}</p>
            @endif
            @if(isset($data['usuario_genera']))
                <p><strong>Generado por:</strong> {{ $data['usuario_genera'] }}</p>
            @endif
        </div>
    </div>

    @if(file_exists(public_path('img/ser(1).png')))
        <footer>
            <img src="{{ public_path('img/ser(1).png') }}" alt="Olas">
        </footer>
    @endif
</body>
</html>