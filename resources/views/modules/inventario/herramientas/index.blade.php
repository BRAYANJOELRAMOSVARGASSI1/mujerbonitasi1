@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="card mb-4" style="border:none; border-radius:12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
        <div class="card-header" style="background-color:white; border-radius:12px 12px 0 0; border-bottom:1px solid #dee2e6;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 style="margin:0; font-weight:600; color:#212529;">
                        <svg class="icon me-2" style="color:#CC5CB8;"><use xlink:href="{{ asset('icons/coreui.svg#cil-settings') }}"></use></svg>Herramientas del Salón
                    </h5>
                    <small style="opacity:0.7;">p3-gestion de inventario y herramientas | CU7 — Registrar Herramienta | CU13 — Consultar Herramientas</small>
                </div>
                <a href="{{ route('herramientas.create') }}" class="btn" style="background-color:#CC5CB8; color:white; border:none; border-radius:8px; padding:8px 16px; font-weight:500;">
                    <svg class="icon me-2"><use xlink:href="{{ asset('icons/coreui.svg#cil-plus') }}"></use></svg>Nueva Herramienta
                </a>
            </div>
        </div>
        <div class="card-body" style="background-color:#f8f9fa; padding:1.5rem;">
            @if (session('status'))
                <div class="alert alert-success" style="border:none; border-radius:8px; background-color:#d4edda; color:#155724; border-left:4px solid #28a745;">{{ session('status') }}</div>
            @endif
            <form method="GET" action="{{ route('herramientas.index') }}" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-3"><input type="text" name="buscar" class="form-control" placeholder="Buscar..." value="{{ request('buscar') }}" style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;"></div>
                    <div class="col-md-3">
                        <select name="categoria" class="form-select" style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                            <option value="">Todas las categorías</option>
                            @foreach($categorias as $cat)<option value="{{ $cat }}" {{ request('categoria') == $cat ? 'selected' : '' }}>{{ $cat }}</option>@endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="estado" class="form-select" style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                            <option value="">Todos</option>
                            @foreach($estados as $key => $label)<option value="{{ $key }}" {{ request('estado') == $key ? 'selected' : '' }}>{{ $label }}</option>@endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="area" class="form-select" style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                            <option value="">Todas las áreas</option>
                            @foreach($areas as $a)<option value="{{ $a }}" {{ request('area') == $a ? 'selected' : '' }}>{{ $a }}</option>@endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn flex-fill" style="background-color:#CC5CB8; color:white; border:none; border-radius:8px;">Buscar</button>
                        <a href="{{ route('herramientas.index') }}" class="btn" style="background-color:#6c757d; color:white; border:none; border-radius:8px;"><svg class="icon"><use xlink:href="{{ asset('icons/coreui.svg#cil-reload') }}"></use></svg></a>
                    </div>
                </div>
            </form>
            @if($herramientas->count() > 0)
            <div class="table-responsive">
                <table class="table" style="background:white; border-radius:8px; overflow:hidden;">
                    <thead style="background-color:#CC5CB8; color:white;"><tr><th>Nombre</th><th>Categoría</th><th>Área</th><th>N° Serie</th><th>Estado</th><th>Acciones</th></tr></thead>
                    <tbody>
                        @foreach($herramientas as $h)
                        @php $estadoColor = match($h->estado) { 'disponible' => '#28a745', 'en_uso' => '#17a2b8', 'mantenimiento' => '#ffc107', 'baja' => '#dc3545', default => '#6c757d' }; @endphp
                        <tr>
                            <td style="font-weight:500;">{{ $h->nombre }}</td>
                            <td><span class="badge" style="background-color:#CC5CB8;">{{ $h->categoria }}</span></td>
                            <td>{{ $h->area_asignada ?? '—' }}</td>
                            <td><small style="color:#6c757d;">{{ $h->numero_serie ?? '—' }}</small></td>
                            <td><span class="badge" style="background-color:{{ $estadoColor }};">{{ ucfirst(str_replace('_', ' ', $h->estado)) }}</span></td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('herramientas.show', $h) }}" class="btn btn-sm" style="background-color:#17a2b8; color:white; border:none; border-radius:6px; padding:4px 10px;"><svg class="icon" style="width:14px; height:14px;"><use xlink:href="{{ asset('icons/coreui.svg#cil-eye') }}"></use></svg></a>
                                    <a href="{{ route('herramientas.edit', $h) }}" class="btn btn-sm" style="background-color:#ffc107; color:#212529; border:none; border-radius:6px; padding:4px 10px;"><svg class="icon" style="width:14px; height:14px;"><use xlink:href="{{ asset('icons/coreui.svg#cil-pencil') }}"></use></svg></a>
                                    <form method="POST" action="{{ route('herramientas.destroy', $h) }}" onsubmit="return confirm('¿Eliminar?')">@csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm" style="background-color:#dc3545; color:white; border:none; border-radius:6px; padding:4px 10px;"><svg class="icon" style="width:14px; height:14px;"><use xlink:href="{{ asset('icons/coreui.svg#cil-trash') }}"></use></svg></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
                <div class="text-center py-5"><h5 style="color:#495057;">No hay herramientas registradas</h5></div>
            @endif
        </div>
        @if($herramientas->count() > 0)<div class="card-footer" style="background-color:#f8f9fa; border:none; padding:1.5rem 2rem;">{{ $herramientas->links() }}</div>@endif
    </div>
</div>
@endsection
