@extends('layouts.guest')

@section('content')
    <div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center" 
         style="background: linear-gradient(rgba(204, 92, 184, 0.8), rgba(56, 20, 50, 0.9)), position: relative;">
        <div class="row w-100 justify-content-center">
            <div class="col-12 col-md-10 col-lg-8 col-xl-6">
                <div class="card" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border: none; border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.1);">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #CC5CB8, #381432); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                                <svg class="icon" style="color: white; width: 40px; height: 40px;">
                                    <use xlink:href="{{ asset('icons/coreui.svg#cil-lock-locked') }}"></use>
                                </svg>
                            </div>
                            <h2 style="color: #212529; font-weight: 700; margin-bottom: 0.5rem;">Recuperar Contraseña</h2>
                            <p style="color: #6c757d; font-size: 1.1rem;">Te enviaremos un enlace de recuperación.</p>
                        </div>
                        
                        <form action="{{ route('password.email') }}" method="POST">
                            @csrf
                            @if(session('status'))
                                <div style="background-color: #d4edda; color: #155724; border-left: 4px solid #28a745; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                                    {{ session('status') }}
                                </div>
                            @endif
                            <div class="mb-4">
                                <label for="email" class="form-label" style="color: #212529; font-weight: 600;">Correo Electrónico</label>
                                <div class="input-group">
                                    <span class="input-group-text" style="background: linear-gradient(135deg, #CC5CB8, #381432); color: white; border: none; border-radius: 12px 0 0 12px;">
                                        <svg class="icon">
                                            <use xlink:href="{{ asset('icons/coreui.svg#cil-envelope-open') }}"></use>
                                        </svg>
                                    </span>
                                    <input class="form-control @error('email') is-invalid @enderror" type="email"
                                           id="email" name="email" value="{{ old('email') }}" placeholder="Ingresa tu correo registrado" style="border: 2px solid #e9ecef; border-radius: 0 12px 12px 0; padding: 0.75rem; background: rgba(255, 255, 255, 0.9);" required autofocus>
                                </div>
                                @error('email')
                                    <div class="invalid-feedback d-block" style="color: #dc3545; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button class="btn btn-lg" type="submit"
                                        style="background: linear-gradient(135deg, #CC5CB8, #381432); color: white; border: none; border-radius: 12px; padding: 0.75rem; font-weight: 600; font-size: 1.1rem; transition: all 0.3s ease;"
                                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(204, 92, 184, 0.3)'"
                                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                    Enviar enlace
                                </button>
                            </div>
                            
                            <div class="text-center mt-4">
                                <p style="color: #6c757d; margin-bottom: 0.5rem;">¿Recordaste tu contraseña?</p>
                                <a href="{{ route('login') }}" 
                                   style="color: #CC5CB8; text-decoration: none; font-weight: 600; font-size: 1rem;"
                                   onmouseover="this.style.color='#381432'"
                                   onmouseout="this.style.color='#CC5CB8'">
                                    Volver al Login
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
