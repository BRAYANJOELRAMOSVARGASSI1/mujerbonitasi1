@extends('layouts.app')

@section('content')
    <div class="card mb-4" style="border:none; border-radius:12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
        <div class="card-header" style="background-color:#CC5CB8; color:white; border-radius:12px 12px 0 0; border:none;">
            <h5 style="margin:0; font-weight:600;">Editar Usuario: {{ $user->name }}</h5>
        </div>
        
        <div class="card-body" style="background-color:#f8f9fa; padding:2rem;">
            <form method="POST" action="{{ route('users.update', $user) }}">
                @csrf
                @method('PUT')
                
                {{-- NOMBRE --}}
                <div class="mb-3">
                    <label for="name" class="form-label" style="color: #212529; font-weight: 600;">Nombre</label>
                    <div class="input-group">
                        <span class="input-group-text" style="background-color:#CC5CB8; color:white; border:1px solid #CC5CB8;">
                            <svg class="icon">
                                <use xlink:href="{{ asset('icons/coreui.svg#cil-user') }}"></use>
                            </svg>
                        </span>
                        <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" 
                               id="name" value="{{ old('name', $user->name) }}" required
                               autocomplete="name" autofocus
                               style="border:1px solid #dee2e6; border-radius:0 8px 8px 0; padding:0.75rem;">
                    </div>
                    @error('name')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                
                {{-- EMAIL --}}
                <div class="mb-3">
                    <label for="email" class="form-label" style="color: #212529; font-weight: 600;">Email</label>
                    <div class="input-group">
                        <span class="input-group-text" style="background-color:#CC5CB8; color:white; border:1px solid #CC5CB8;">
                            <svg class="icon">
                                <use xlink:href="{{ asset('icons/coreui.svg#cil-envelope-open') }}"></use>
                            </svg>
                        </span>
                        <input class="form-control @error('email') is-invalid @enderror" type="email" name="email" 
                               id="email" value="{{ old('email', $user->email) }}" required
                               autocomplete="email"
                               style="border:1px solid #dee2e6; border-radius:0 8px 8px 0; padding:0.75rem;">
                    </div>
                    @error('email')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                
                {{-- CONTRASEÑA --}}
                <div class="mb-3">
                    <label for="password" class="form-label" style="color: #212529; font-weight: 600;">Nueva Contraseña</label>
                    <div class="input-group">
                        <span class="input-group-text" style="background-color:#CC5CB8; color:white; border:1px solid #CC5CB8;">
                            <svg class="icon">
                                <use xlink:href="{{ asset('icons/coreui.svg#cil-lock-locked') }}"></use>
                            </svg>
                        </span>
                        <input class="form-control @error('password') is-invalid @enderror" type="password"
                               name="password" id="password" placeholder="Dejar vacío para mantener la contraseña actual"
                               style="border:1px solid #dee2e6; border-radius:0 8px 8px 0; padding:0.75rem;">
                        <button type="button" class="btn" id="togglePassword" 
                                style="background-color:#CC5CB8; color:white; border:1px solid #CC5CB8; padding:0.75rem;">
                            <svg class="icon" style="width: 20px; height: 20px;">
                                <use xlink:href="{{ asset('icons/coreui.svg#cil-eye') }}"></use>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                
                {{-- CONFIRMAR CONTRASEÑA --}}
                <div class="mb-4">
                    <label for="password_confirmation" class="form-label" style="color: #212529; font-weight: 600;">Confirmar Nueva Contraseña</label>
                    <div class="input-group">
                        <span class="input-group-text" style="background-color:#CC5CB8; color:white; border:1px solid #CC5CB8;">
                            <svg class="icon">
                                <use xlink:href="{{ asset('icons/coreui.svg#cil-lock-locked') }}"></use>
                            </svg>
                        </span>
                        <input class="form-control @error('password_confirmation') is-invalid @enderror" type="password"
                               name="password_confirmation" id="password_confirmation" 
                               style="border:1px solid #dee2e6; border-radius:0 8px 8px 0; padding:0.75rem;">
                    </div>
                    @error('password_confirmation')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="card-footer" style="background-color:#f8f9fa; border:none; text-align:right; padding:1.5rem 0;">
                    <a href="{{ route('users.index', $user) }}" class="btn me-2" 
                       style="background-color:#6c757d; color:white; border:none; border-radius:8px; padding:0.5rem 1.5rem; font-weight:500;">
                        Cancelar
                    </a>
                    
                    <button type="submit" class="btn" 
                            style="background-color:#17a2b8; color:white; border:none; border-radius:8px; padding:0.5rem 1.5rem; font-weight:500;">
                        {{ __('Actualizar Usuario') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('svg use');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            if (type === 'password') {
                icon.setAttribute('xlink:href', '{{ asset('icons/coreui.svg#cil-eye') }}');
            } else {
                icon.setAttribute('xlink:href', '{{ asset('icons/coreui.svg#cil-eye-slash') }}');
            }
        });
    </script>
@endsection
