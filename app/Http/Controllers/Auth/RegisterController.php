<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller — Sistema MUJER BONITA
    |--------------------------------------------------------------------------
    |
    | Maneja el registro de nuevos usuarios públicos (rol: cliente).
    | Aplica validación de contraseñas seguras:
    |   • Mínimo 8 caracteres
    |   • Al menos 1 letra mayúscula
    |   • Al menos 1 letra minúscula
    |   • Al menos 1 número
    |
    */

    use RegistersUsers;

    /**
     * Redirección tras registro exitoso.
     */
    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Validación avanzada para registro de nuevos usuarios.
     *
     * Regla de contraseña: Password::min(8)->letters()->mixedCase()->numbers()
     * Garantiza seguridad mínima profesional.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => [
                'required',
                'string',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers(),
            ],
        ], [
            // Mensajes personalizados en español
            'name.required'       => 'El nombre es obligatorio.',
            'email.required'      => 'El correo electrónico es obligatorio.',
            'email.email'         => 'Ingresa un correo electrónico válido.',
            'email.unique'        => 'Este correo electrónico ya está registrado.',
            'password.required'   => 'La contraseña es obligatoria.',
            'password.confirmed'  => 'Las contraseñas no coinciden.',
            'password.min'        => 'La contraseña debe tener al menos 8 caracteres.',
        ]);
    }

    /**
     * Crea el usuario y le asigna automáticamente el rol 'cliente'.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'status'   => 'activo',
        ]);

        // Asignar rol cliente automáticamente al registrarse
        $user->assignRole('cliente');

        return $user;
    }
}
