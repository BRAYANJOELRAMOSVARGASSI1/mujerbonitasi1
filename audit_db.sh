#!/bin/bash
cd /var/www/html
sudo php artisan tinker << 'PHPEOF'
$db = DB::connection();

// Pagos
$pagosTotal = $db->table('pagos')->count();
$pagosComp = $db->table('pagos')->where('estado_pago','completado')->count();
$pagosMes = $db->table('pagos')->where('estado_pago','completado')
    ->where('updated_at','>=', now()->startOfMonth())->count();

// Servicios realizados
$srTotal = $db->table('servicios_realizados')->count();
$srMes = $db->table('servicios_realizados')
    ->where('fecha_realizacion','>=', now()->startOfMonth()->toDateString())->count();
$srIngresos = $db->table('servicios_realizados')
    ->where('fecha_realizacion','>=', now()->startOfMonth()->toDateString())
    ->sum('precio_cobrado');

// Citas
$citasTotal = $db->table('citas')->count();
$citasMes = $db->table('citas')->where('fecha','>=', now()->startOfMonth()->toDateString())->count();

// Clientes / Productos
$clientes = $db->table('clientes')->count();
$productos = $db->table('productos')->count();

echo "=== DATOS PRODUCCION ===\n";
echo "PAGOS Total: $pagosTotal | Completados: $pagosComp | Este mes: $pagosMes\n";
echo "SERVICIOS REALIZADOS Total: $srTotal | Este mes: $srMes | Ingresos mes: $srIngresos\n";
echo "CITAS Total: $citasTotal | Este mes: $citasMes\n";
echo "CLIENTES: $clientes | PRODUCTOS: $productos\n";

echo "\n=== ULTIMOS 5 PAGOS ===\n";
$ultPagos = $db->table('pagos')->orderByDesc('updated_at')->limit(5)
    ->get(['id','monto','estado_pago','metodo','updated_at']);
foreach($ultPagos as $p) {
    echo "ID:{$p->id} monto:{$p->monto} estado:{$p->estado_pago} metodo:{$p->metodo} fecha:{$p->updated_at}\n";
}

echo "\n=== ULTIMOS 5 SERVICIOS REALIZADOS ===\n";
$ultSR = $db->table('servicios_realizados')->orderByDesc('fecha_realizacion')->limit(5)
    ->get(['id','precio_cobrado','fecha_realizacion','estilista_id','cliente_id']);
foreach($ultSR as $s) {
    echo "ID:{$s->id} precio:{$s->precio_cobrado} fecha:{$s->fecha_realizacion} estilista:{$s->estilista_id} cliente:{$s->cliente_id}\n";
}

echo "\n=== PAGOS CON CITA (whereHas check) ===\n";
$pagosConCita = $db->table('pagos')
    ->join('citas', 'pagos.cita_id', '=', 'citas.id')
    ->count();
echo "Pagos que tienen cita_id valido: $pagosConCita\n";

exit;
PHPEOF
