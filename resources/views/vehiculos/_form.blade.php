{{-- Campos del vehículo para las páginas de alta y edición (sin AJAX). --}}
@php $v = $vehiculo ?? null; @endphp

@if(session('error'))
    <div class="alert alert-danger" role="alert">
        <i class="bi bi-exclamation-circle-fill" aria-hidden="true"></i><div>{{ session('error') }}</div>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger" role="alert">
        <i class="bi bi-exclamation-circle-fill" aria-hidden="true"></i>
        <div>
            <strong class="d-block mb-1">Revisa los campos marcados</strong>
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    </div>
@endif

<div class="ft-panel__body">
    <h2 class="form-section-title">Identificación</h2>
    <div class="row g-3">
        <div class="col-md-6">
            <label for="id_lugar" class="form-label required">Lugar</label>
            <select id="id_lugar" name="id_lugar" class="form-select @error('id_lugar') is-invalid @enderror" required>
                <option value="">Selecciona un lugar</option>
                @foreach($lugares as $lugar)
                    <option value="{{ $lugar->id_lugar }}" @selected(old('id_lugar', $v->id_lugar ?? Auth::user()->id_lugar) == $lugar->id_lugar)>{{ $lugar->nombre }}</option>
                @endforeach
            </select>
            @error('id_lugar')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label for="eco" class="form-label required">No. ECO</label>
            <input type="text" id="eco" name="eco" class="form-control ft-code @error('eco') is-invalid @enderror" value="{{ old('eco', $v->eco ?? '') }}" required>
            @error('eco')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label for="placas" class="form-label">Placas</label>
            <input type="text" id="placas" name="placas" class="form-control ft-code" maxlength="20" value="{{ old('placas', $v->placas ?? '') }}">
        </div>
        <div class="col-md-6">
            <label for="estatus" class="form-label required">Estado</label>
            @php $estatus = old('estatus', $v->estatus ?? 'activo'); @endphp
            <select id="estatus" name="estatus" class="form-select" required>
                <option value="activo" @selected($estatus === 'activo')>Activo</option>
                <option value="inactivo" @selected($estatus === 'inactivo')>Inactivo</option>
                <option value="mantenimiento" @selected($estatus === 'mantenimiento')>En Mantenimiento</option>
            </select>
        </div>
    </div>

    <h2 class="form-section-title">Unidad</h2>
    <div class="row g-3">
        <div class="col-md-6">
            <label for="marca" class="form-label">Marca</label>
            <input type="text" id="marca" name="marca" class="form-control" maxlength="50" value="{{ old('marca', $v->marca ?? '') }}">
        </div>
        <div class="col-md-6">
            <label for="modelo" class="form-label">Modelo</label>
            <input type="text" id="modelo" name="modelo" class="form-control" maxlength="50" value="{{ old('modelo', $v->modelo ?? '') }}">
        </div>
        <div class="col-6 col-md-4">
            <label for="anio" class="form-label">Año</label>
            <input type="text" id="anio" name="anio" class="form-control" maxlength="4" inputmode="numeric" value="{{ old('anio', $v->anio ?? '') }}">
        </div>
        <div class="col-6 col-md-4">
            <label for="kilometraje" class="form-label">Kilometraje</label>
            <input type="number" id="kilometraje" name="kilometraje" class="form-control" min="0" inputmode="numeric" value="{{ old('kilometraje', $v->kilometraje ?? '') }}">
        </div>
        <div class="col-md-4">
            <label for="color" class="form-label">Color</label>
            <input type="text" id="color" name="color" class="form-control" maxlength="30" value="{{ old('color', $v->color ?? '') }}">
        </div>
        <div class="col-12">
            <label for="conductor_habitual" class="form-label">Conductor habitual</label>
            <input type="text" id="conductor_habitual" name="conductor_habitual" class="form-control" maxlength="100" value="{{ old('conductor_habitual', $v->conductor_habitual ?? '') }}">
        </div>
    </div>
</div>
