#!/bin/bash
cd /var/www/html

echo "=== CHECK TIMEZONE ==="
sudo php artisan tinker << 'PHPEOF'
echo "App Timezone: " . config('app.timezone') . "\n";
echo "DB Now(): ";
$r = DB::select('SELECT NOW() as n, CURDATE() as d');
echo $r[0]->n . " | " . $r[0]->d . "\n";
echo "Carbon now: " . \Carbon\Carbon::now() . "\n";
echo "Carbon startOfMonth: " . \Carbon\Carbon::now()->startOfMonth() . "\n";
echo "Carbon endOfDay: " . \Carbon\Carbon::now()->endOfDay() . "\n";

// Simulate what the controller does (default dates)
$fechaInicio = \Carbon\Carbon::now()->startOfMonth();
$fechaFin = \Carbon\Carbon::now()->endOfDay();
echo "Query range: $fechaInicio to $fechaFin\n";

// Check what servicios_realizados fall in that range
$count = DB::table('servicios_realizados')
    ->whereBetween('fecha_realizacion', [$fechaInicio, $fechaFin])
    ->count();
echo "Servicios en ese rango: $count\n";

$pagos = DB::table('pagos')
    ->where('estado_pago','completado')
    ->whereBetween('updated_at', [$fechaInicio, $fechaFin])
    ->count();
echo "Pagos completados en ese rango: $pagos\n";
exit;
PHPEOF
