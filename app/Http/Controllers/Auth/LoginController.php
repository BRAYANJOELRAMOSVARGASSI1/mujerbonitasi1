<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    public function login(Request $request)
    {
        // Validar que no haya campos vacíos (mensaje en español neutro se configura aquí)
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ], [
            $this->username().'.required' => 'El campo correo es obligatorio.',
            'password.required' => 'El campo contraseña es obligatorio.',
        ]);

        $user = User::where($this->username(), $request->{$this->username()})->first();

        // 1. Bloqueo Permanente (Nivel BD) según requerimiento estricto
        if ($user && $user->status === 'bloqueado') {
            throw ValidationException::withMessages([
                $this->username() => ['Tu cuenta ha sido bloqueada por seguridad tras 5 intentos fallidos. Contacta al Administrador para desbloquearla.'],
            ]);
        }

        // 2. Control de Estrangulamiento de Laravel (Caché por minutos)
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        // 3. Intento Exitoso
        if ($this->attemptLogin($request)) {
            if ($request->hasSession()) {
                $request->session()->put('auth.password_confirmed_at', time());
            }
            if ($user && $user->failed_logins > 0) {
                $user->update(['failed_logins' => 0]); 
            }
            return $this->sendLoginResponse($request);
        }

        // 4. Intento Fallido y Bloqueo
        if ($user) {
            $user->increment('failed_logins');
            $attempt = $user->failed_logins;
            
            // Identificación de Roles
            $isSuper = $user->hasRole(['super-admin', 'Super Admin']);
            $isAdmin = $user->hasRole(['admin', 'Admin', 'Administrador']);
            $isStaff = $user->hasRole(['recepcionista', 'estilista', 'staff']);
            $isClient = $user->hasRole(['cliente', 'client']) || (!$isSuper && !$isAdmin && !$isStaff);

            // Umbrales de Seguridad
            if ($isSuper) {
                if ($attempt >= 20) {
                    $this->limiter()->hit($this->throttleKey($request), 3600); // 1 hora por seguridad extrema
                    throw ValidationException::withMessages([
                        $this->username() => ['Alerta de Seguridad Extrema: Demasiados intentos fallidos para el Súper Admin.'],
                    ]);
                }
                // El Super Admin no tiene bloqueos de tiempo cortos para evitar auto-bloqueo
            } 
            elseif ($isAdmin) {
                if ($attempt >= 5) {
                    $this->limiter()->hit($this->throttleKey($request), 1800); // 30 min
                    throw ValidationException::withMessages([
                        $this->username() => ['Demasiados intentos. Tu cuenta de Administrador está protegida por 30 minutos.'],
                    ]);
                } elseif ($attempt >= 3) {
                    $this->limiter()->hit($this->throttleKey($request), 300); // 5 min
                    throw ValidationException::withMessages([
                        $this->username() => ['Contraseña incorrecta. Se ha activado un bloqueo temporal de 5 minutos por seguridad.'],
                    ]);
                }
            }
            elseif ($isStaff) {
                if ($attempt >= 5) {
                    $this->limiter()->hit($this->throttleKey($request), 900); // 15 min
                    throw ValidationException::withMessages([
                        $this->username() => ['Demasiados intentos. Tu cuenta de Estilista/Staff está protegida por 15 minutos.'],
                    ]);
                } elseif ($attempt >= 3) {
                    $this->limiter()->hit($this->throttleKey($request), 300); // 5 min
                    throw ValidationException::withMessages([
                        $this->username() => ['Contraseña incorrecta. Se ha activado un bloqueo temporal de 5 minutos por seguridad.'],
                    ]);
                }
            }
            elseif ($isClient) {
                if ($attempt >= 5) {
                    $user->update(['status' => 'bloqueado']);
                    throw ValidationException::withMessages([
                        $this->username() => ['Su cuenta ha sido BLOQUEADA por seguridad tras 5 intentos fallidos. Por favor, contacte a un Administrador para desbloquearla.'],
                    ]);
                } elseif ($attempt >= 3) {
                    $this->limiter()->hit($this->throttleKey($request), 600); // 10 min
                    throw ValidationException::withMessages([
                        $this->username() => ['Advertencia: Demasiados intentos erróneos. Su cuenta está bloqueada temporalmente por 10 minutos.'],
                    ]);
                }
            }

            // Si no ha llegado a un umbral de bloqueo de tiempo, incrementar intentos estándar de Laravel
            $this->incrementLoginAttempts($request);

            throw ValidationException::withMessages([
                $this->username() => ['Contraseña equivocada. Inténtalo de nuevo.'],
            ]);

        } else {
            $this->incrementLoginAttempts($request);
            
            throw ValidationException::withMessages([
                $this->username() => ['Credenciales no encontradas.'],
            ]);
        }
    }

    /**
     * Redirección Dinámica a partir del Rol
     */
    protected function authenticated(Request $request, $user)
    {
        if ($user->hasRole(['admin', 'Admin', 'Administrador', 'Super Admin', 'super-admin'])) {
            return redirect('/users');
        }

        return redirect('/home');
    }
}
