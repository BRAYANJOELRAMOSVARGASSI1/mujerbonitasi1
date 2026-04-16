@extends('layouts.app')

@section('content')
    <div class="card mb-4" style="border:none; border-radius:12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
        <div class="card-header" style="background-color:#CC5CB8; color:white; border-radius:12px 12px 0 0; border:none;">
            <h5 style="margin:0; font-weight:600;">Crear Nuevo Permiso</h5>
        </div>
        
        <div class="card-body" style="background-color:#f8f9fa; padding:2rem;">
            <form method="POST" action="{{ route('permissions.store') }}">
                @csrf
                
                {{-- NOMBRE DEL PERMISO --}}
                <div class="mb-4">
                    <label for="name" class="form-label" style="color: #212529; font-weight: 600;">Nombre del Permiso</label>
                    <div class="input-group">
                        <span class="input-group-text" style="background-color:#6c757d; color:white; border:1px solid #6c757d;">
                            <svg class="icon">
                                <use xlink:href="{{ asset('icons/coreui.svg#cil-vpn') }}"></use>
                            </svg>
                        </span>
                        <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" 
                               id="name" value="{{ old('name') }}" required
                               placeholder="ej. crear-usuario, editar-catalogo"
                               style="border:1px solid #dee2e6; border-radius:0 8px 8px 0; padding:0.75rem;">
                    </div>
                    @error('name')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Use kebab-case separado por guiones (ej. crear-usuarios, editar-rol)</small>
                </div>
                
                <div class="card-footer" style="background-color:#f8f9fa; border:none; text-align:right; padding:1.5rem 0;">
                    <a href="{{ route('permissions.index') }}" class="btn me-2" 
                       style="background-color:#6c757d; color:white; border:none; border-radius:8px; padding:0.5rem 1.5rem; font-weight:500;">
                        Cancelar
                    </a>
                    
                    <button type="submit" class="btn" 
                            style="background-color:#28a745; color:white; border:none; border-radius:8px; padding:0.5rem 1.5rem; font-weight:500;">
                        {{ __('Crear Permiso') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
