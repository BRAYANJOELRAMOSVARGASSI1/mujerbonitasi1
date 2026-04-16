@extends('layouts.guest')

@section('content')
    <div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center" 
         style="background: linear-gradient(rgba(204, 92, 184, 0.8), rgba(56, 20, 50, 0.9)), position: relative;">
        <div class="row w-100 justify-content-center">
            <div class="col-12 col-md-10 col-lg-8 col-xl-6">
                <div class="card" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border: none; border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.1);">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <h2 style="color: #212529; font-weight: 700; margin-bottom: 0.5rem;">Establecer Nueva Contraseña</h2>
                        </div>
                        
                        <form action="{{ route('password.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="mb-3">
                                <label for="email" class="form-label" style="color: #212529; font-weight: 600;">Correo Electrónico</label>
                                <div class="input-group">
                                    <span class="input-group-text" style="background: linear-gradient(135deg, #CC5CB8, #381432); color: white; border: none; border-radius: 12px 0 0 12px;">
                                        <svg class="icon">
                                            <use xlink:href="{{ asset('icons/coreui.svg#cil-envelope-open') }}"></use>
                                        </svg>
                                    </span>
                                    <input class="form-control @error('email') is-invalid @enderror" type="email"
                                           id="email" name="email" value="{{ $email ?? old('email') }}" style="border: 2px solid #e9ecef; border-radius: 0 12px 12px 0; padding: 0.75rem; background: rgba(255, 255, 255, 0.9);" required readonly>
                                </div>
                                @error('email')
                                    <div class="invalid-feedback d-block" style="color: #dc3545; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label" style="color: #212529; font-weight: 600;">Nueva Contraseña</label>
                                <div class="input-group">
                                    <span class="input-group-text" style="background: linear-gradient(135deg, #CC5CB8, #381432); color: white; border: none; border-radius: 12px 0 0 12px;">
                                        <svg class="icon">
                                            <use xlink:href="{{ asset('icons/coreui.svg#cil-lock-locked') }}"></use>
                                        </svg>
                                    </span>
                                    <input class="form-control @error('password') is-invalid @enderror" type="password"
                                           id="password" name="password" placeholder="Mínimo 8 caracteres" style="border: 2px solid #e9ecef; border-radius: 0 12px 12px 0; padding: 0.75rem; background: rgba(255, 255, 255, 0.9);" required autofocus>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block" style="color: #dc3545; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label" style="color: #212529; font-weight: 600;">Confirmar Contraseña</label>
                                <div class="input-group">
                                    <span class="input-group-text" style="background: linear-gradient(135deg, #CC5CB8, #381432); color: white; border: none; border-radius: 12px 0 0 12px;">
                                        <svg class="icon">
                                            <use xlink:href="{{ asset('icons/coreui.svg#cil-lock-locked') }}"></use>
                                        </svg>
                                    </span>
                                    <input class="form-control @error('password_confirmation') is-invalid @enderror" type="password"
                                           id="password_confirmation" name="password_confirmation" placeholder="Escribe tu contraseña nuevamente" style="border: 2px solid #e9ecef; border-radius: 0 12px 12px 0; padding: 0.75rem; background: rgba(255, 255, 255, 0.9);" required>
                                </div>
                                @error('password_confirmation')
                                    <div class="invalid-feedback d-block" style="color: #dc3545; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button class="btn btn-lg" type="submit"
                                        style="background: linear-gradient(135deg, #CC5CB8, #381432); color: white; border: none; border-radius: 12px; padding: 0.75rem; font-weight: 600; font-size: 1.1rem; transition: all 0.3s ease;">
                                    Guardar Contraseña
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection