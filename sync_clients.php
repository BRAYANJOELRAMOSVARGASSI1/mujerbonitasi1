$users = \App\Models\User::role('cliente')->get();
foreach ($users as $user) {
    if (!\App\Modules\P2_GestionPersonalClientes\Models\Cliente::where('email', $user->email)->exists()) {
        $n = explode(' ', trim($user->name), 2);
        \App\Modules\P2_GestionPersonalClientes\Models\Cliente::create([
            'nombre'   => $n[0],
            'apellido' => $n[1] ?? '',
            'email'    => $user->email,
            'telefono' => '00000000',
            'estado'   => 'activo'
        ]);
        echo "Sincronizado remotamente: " . $user->email . "\n";
    }
}
