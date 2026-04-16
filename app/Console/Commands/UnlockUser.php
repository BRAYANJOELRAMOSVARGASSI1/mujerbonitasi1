<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class UnlockUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:unlock {email : El correo del usuario a desbloquear}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Desbloquea permanentemente un usuario que ha superado los intentos fallidos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("No se encontró ningún usuario con el correo: {$email}");
            return 1;
        }

        $user->update([
            'status' => 'activo',
            'failed_logins' => 0
        ]);

        $this->info("¡Usuario {$email} desbloqueado con éxito!");
        $this->info("Estado: Activo | Intentos fallidos: 0");

        return 0;
    }
}
