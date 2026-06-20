<?php

namespace App\Modules\P6_ReportesComunicaciones\Mail;

use App\Modules\P4_GestionServiciosCitas\Models\Promocion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PromocionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $promocion;
    public $cliente;

    /**
     * Create a new message instance.
     */
    public function __construct(Promocion $promocion, $cliente)
    {
        $this->promocion = $promocion;
        $this->cliente = $cliente;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '¡Nueva Promoción Especial en Mujer Bonita!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.promocion',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
