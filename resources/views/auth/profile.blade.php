@extends('layouts.app')

@section('content')
    <div class="card mb-4" style="border:none; border-radius:12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
        <div class="card-header" style="background-color:#CC5CB8; color:white; border-radius:12px 12px 0 0; border:none;">
            <h5 style="margin:0; font-weight:600;">{{ __('Mi Perfil') }}</h5>
        </div>

        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card-body" style="background-color:#f8f9fa; padding:2rem;">

                @if ($message = Session::get('success'))
                    <div class="alert alert-success" role="alert" style="border:none; border-radius:8px; background-color:#d4edda; color:#155724; border-left:4px solid #28a745;">{{ $message }}</div>
                @endif

                <div class="mb-3">
                    <label for="name" class="form-label" style="color:#212529; font-weight:500;">Nombre</label>
                    <div class="input-group">
                        <span class="input-group-text" style="background-color:#CC5CB8; color:white; border:1px solid #CC5CB8;">
                            <svg class="icon">
                                <use xlink:href="{{ asset('icons/coreui.svg#cil-user') }}"></use>
                            </svg>
                        </span>
                        <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" 
                               placeholder="{{ __('Nombre') }}" value="{{ old('name', auth()->user()->name) }}" required
                               style="border:1px solid #dee2e6; border-radius:0 8px 8px 0; padding:0.75rem;">
                    </div>
                    @error('name')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label" style="color:#212529; font-weight:500;">Email</label>
                    <div class="input-group">
                        <span class="input-group-text" style="background-color:#CC5CB8; color:white; border:1px solid #CC5CB8;">
                            <svg class="icon">
                                <use xlink:href="{{ asset('icons/coreui.svg#cil-envelope-open') }}"></use>
                            </svg>
                        </span>
                        <input class="form-control @error('email') is-invalid @enderror" type="email" name="email" 
                               placeholder="{{ __('Email') }}" value="{{ old('email', auth()->user()->email) }}" required
                               style="border:1px solid #dee2e6; border-radius:0 8px 8px 0; padding:0.75rem;">
                    </div>
                    @error('email')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label" style="color:#212529; font-weight:500;">Nueva Contraseña</label>
                    <div class="input-group">
                        <span class="input-group-text" style="background-color:#CC5CB8; color:white; border:1px solid #CC5CB8;">
                            <svg class="icon">
                                <use xlink:href="{{ asset('icons/coreui.svg#cil-lock-locked') }}"></use>
                            </svg>
                        </span>
                        <input class="form-control @error('password') is-invalid @enderror" type="password"
                               name="password" placeholder="{{ __('Nueva contraseña') }}"
                               style="border:1px solid #dee2e6; border-radius:0 8px 8px 0; padding:0.75rem;">
                    </div>
                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label" style="color:#212529; font-weight:500;">Confirmar Nueva Contraseña</label>
                    <div class="input-group">
                        <span class="input-group-text" style="background-color:#CC5CB8; color:white; border:1px solid #CC5CB8;">
                            <svg class="icon">
                                <use xlink:href="{{ asset('icons/coreui.svg#cil-lock-locked') }}"></use>
                            </svg>
                        </span>
                        <input class="form-control @error('password_confirmation') is-invalid @enderror" type="password"
                               name="password_confirmation" placeholder="{{ __('Confirmar nueva contraseña') }}"
                               style="border:1px solid #dee2e6; border-radius:0 8px 8px 0; padding:0.75rem;">
                    </div>
                    @error('password_confirmation')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <div class="card-footer" style="background-color:#f8f9fa; border:none; border-radius:0 0 12px 12px; padding:1.5rem 2rem;">
                <button class="btn" type="submit" 
                        style="background-color:#CC5CB8; color:white; border:none; border-radius:8px; padding:0.5rem 1.5rem; font-weight:500;">
                    {{ __('Actualizar Perfil') }}
                </button>
            </div>

        </form>

    </div>
@endsection
