<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Models\ActivityLog;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    /**
     * Send a reset link to the given user.
     * Sobrescrito para registrar el evento de solicitud en la Bitácora (Para auditoría de Magaly).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);

        // Registro de Auditoría en Bitácora ANTES de enviar (o intentar enviar)
        ActivityLog::create([
            'user_id' => null, // Opcional: Podríamos resolver el ID si existe, pero dejarlo null está permitido ahora
            'action' => 'Solicitud de recuperación de contraseña',
            'description' => 'Un usuario solicitó restablecer contraseña para el correo: ' . $request->email,
            'ip_address' => $request->ip() ?? 'No disponible',
            'browser' => $request->header('user-agent') ?? 'No disponible',
        ]);

        $response = $this->broker()->sendResetLink(
            $this->credentials($request)
        );

        return $response == Password::RESET_LINK_SENT
                    ? $this->sendResetLinkResponse($request, $response)
                    : $this->sendResetLinkFailedResponse($request, $response);
    }

    /**
     * Respuesta exitosa (Mensaje en Español Neutro)
     */
    protected function sendResetLinkResponse(Request $request, $response)
    {
        return back()->with('status', 'Te hemos enviado por correo el enlace para restablecer tu contraseña.');
    }

    /**
     * Respuesta fallida (Correo no registrado, en Español Neutro)
     */
    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'No pudimos encontrar un usuario registrado con ese correo electrónico.']);
    }
}
