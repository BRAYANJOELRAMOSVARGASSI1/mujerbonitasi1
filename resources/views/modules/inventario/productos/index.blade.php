@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="card mb-4" style="border:none; border-radius:12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
        <div class="card-header" style="background-color:white; border-radius:12px 12px 0 0; border-bottom:1px solid #dee2e6;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 style="margin:0; font-weight:600; color:#212529;">
                        <svg class="icon me-2" style="color:#CC5CB8;"><use xlink:href="{{ asset('icons/coreui.svg#cil-basket') }}"></use></svg>Inventario de Productos
                    </h5>
                    <small style="opacity:0.7;">p3-gestion de inventario y herramientas | CU6 — Registrar Producto | CU12 — Consultar Stock</small>
                </div>
                <a href="{{ route('productos.create') }}" class="btn" style="background-color:#CC5CB8; color:white; border:none; border-radius:8px; padding:8px 16px; font-weight:500;">
                    <svg class="icon me-2"><use xlink:href="{{ asset('icons/coreui.svg#cil-plus') }}"></use></svg>Nuevo Producto
                </a>
            </div>
        </div>
        <div class="card-body" style="background-color:#f8f9fa; padding:1.5rem;">
            @if (session('status'))
                <div class="alert alert-success" style="border:none; border-radius:8px; background-color:#d4edda; color:#155724; border-left:4px solid #28a745;">{{ session('status') }}</div>
            @endif
            <form method="GET" action="{{ route('productos.index') }}" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="buscar" class="form-control" placeholder="Buscar por nombre o marca..." value="{{ request('buscar') }}" style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                    </div>
                    <div class="col-md-3">
                        <select name="categoria" class="form-select" style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                            <option value="">Todas las categorías</option>
                            @foreach($categorias as $cat)
                                <option value="{{ $cat }}" {{ request('categoria') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="alerta" class="form-select" style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                            <option value="">Todos los niveles</option>
                            <option value="bajo" {{ request('alerta') == 'bajo' ? 'selected' : '' }}>🟡 Stock Bajo</option>
                            <option value="critico" {{ request('alerta') == 'critico' ? 'selected' : '' }}>🔴 Sin Stock</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn flex-fill" style="background-color:#CC5CB8; color:white; border:none; border-radius:8px;">Buscar</button>
                        <a href="{{ route('productos.index') }}" class="btn" style="background-color:#6c757d; color:white; border:none; border-radius:8px;"><svg class="icon"><use xlink:href="{{ asset('icons/coreui.svg#cil-reload') }}"></use></svg></a>
                    </div>
                </div>
            </form>
            @if($productos->count() > 0)
                <div class="row">
                    @foreach($productos as $prod)
                        @php
                            $alerta = $prod->nivel_alerta;
                            $borderColor = match($alerta) { 'critico' => '#dc3545', 'bajo' => '#ffc107', default => '#28a745' };
                            $alertIcon = match($alerta) { 'critico' => '🔴', 'bajo' => '🟡', default => '🟢' };
                        @endphp
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100" style="border:none; border-radius:12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); border-left:4px solid {{ $borderColor }}; transition: all 0.3s ease;"
                                 onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='translateY(0)'">
                                <div class="card-body" style="padding:1.25rem;">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 style="margin:0; color:#212529; font-weight:600;">{{ $prod->nombre }}</h6>
                                        <span>{{ $alertIcon }}</span>
                                    </div>
                                    <span class="badge mb-2" style="background-color:#CC5CB8; font-size:0.7rem;">{{ $prod->categoria }}</span>
                                    @if($prod->marca)<small style="color:#6c757d; display:block;">Marca: {{ $prod->marca }}</small>@endif
                                    <div style="background-color:#f8f9fa; border-radius:8px; padding:0.75rem; margin-top:0.5rem;">
                                        <div class="d-flex justify-content-between mb-1">
                                            <small><strong>Stock:</strong></small>
                                            <span style="font-weight:600; color:{{ $borderColor }};">{{ $prod->stock_actual }} {{ $prod->unidad_medida ?? 'unid.' }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-1">
                                            <small><strong>Mínimo:</strong></small>
                                            <span>{{ $prod->stock_minimo }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <small><strong>Precio compra:</strong></small>
                                            <span>Bs. {{ number_format($prod->precio_compra, 2) }}</span>
                                        </div>
                                        @if($prod->precio_venta)
                                        <div class="d-flex justify-content-between">
                                            <small><strong>Precio venta:</strong></small>
                                            <span style="color:#28a745; font-weight:600;">Bs. {{ number_format($prod->precio_venta, 2) }}</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-footer" style="background-color:#f8f9fa; border:none; border-radius:0 0 12px 12px; padding:0.75rem 1.25rem;">
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('productos.edit', $prod) }}" class="btn btn-sm" style="background-color:#ffc107; color:#212529; border:none; border-radius:6px; padding:4px 10px;">
                                            <svg class="icon" style="width:14px; height:14px;"><use xlink:href="{{ asset('icons/coreui.svg#cil-pencil') }}"></use></svg> Editar
                                        </a>
                                        <form method="POST" action="{{ route('productos.destroy', $prod) }}" onsubmit="return confirm('¿Eliminar este producto?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm" style="background-color:#dc3545; color:white; border:none; border-radius:6px; padding:4px 10px;">
                                                <svg class="icon" style="width:14px; height:14px;"><use xlink:href="{{ asset('icons/coreui.svg#cil-trash') }}"></use></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <h5 style="color:#495057;">No hay productos registrados</h5>
                    <a href="{{ route('productos.create') }}" class="btn" style="background-color:#CC5CB8; color:white; border:none; border-radius:8px;">Registrar primer producto</a>
                </div>
            @endif
        </div>
        @if($productos->count() > 0)
            <div class="card-footer" style="background-color:#f8f9fa; border:none; padding:1.5rem 2rem;">{{ $productos->links() }}</div>
        @endif
    </div>
</div>
@endsection
