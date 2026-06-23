<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

\Auth::loginUsingId(\App\Models\User::first()->id);
$c = app()->make('App\Modules\P6_ReportesComunicaciones\Controllers\ReportesController');
$req = request();
$req->merge([
    'fecha_inicio' => now()->startOfMonth()->toDateString(),
    'fecha_fin' => now()->endOfDay()->toDateString()
]);

try {
    $res = $c->exportarPdf($req, 'general');
    file_put_contents('public/test.pdf', $res->getContent());
    echo "OK. Type: " . get_class($res) . "\n";
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n" . $e->getTraceAsString();
}
