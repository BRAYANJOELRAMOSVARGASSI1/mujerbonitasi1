#!/bin/bash
cd /var/www/html

echo "=== CONTEOS DE TABLAS ==="
sudo mysql -u$(grep DB_USERNAME .env | cut -d= -f2) -p$(grep DB_PASSWORD .env | cut -d= -f2) $(grep DB_DATABASE .env | cut -d= -f2) -e "
SELECT 
  (SELECT COUNT(*) FROM pagos) as pagos,
  (SELECT COUNT(*) FROM pagos WHERE estado_pago='completado') as pagos_completados,
  (SELECT COUNT(*) FROM servicio_realizado) as servicios_realizados,
  (SELECT COUNT(*) FROM citas) as citas,
  (SELECT COUNT(*) FROM clientes) as clientes,
  (SELECT COUNT(*) FROM productos) as productos,
  (SELECT COUNT(*) FROM pagos WHERE updated_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)) as pagos_ultimo_mes,
  (SELECT COUNT(*) FROM servicio_realizado WHERE fecha_realizacion >= DATE_SUB(NOW(), INTERVAL 30 DAY)) as servicios_ultimo_mes
\G"

echo ""
echo "=== ULTIMOS PAGOS ==="
sudo mysql -u$(grep DB_USERNAME .env | cut -d= -f2) -p$(grep DB_PASSWORD .env | cut -d= -f2) $(grep DB_DATABASE .env | cut -d= -f2) -e "SELECT id, monto, estado_pago, metodo, created_at, updated_at FROM pagos ORDER BY updated_at DESC LIMIT 5;"

echo ""
echo "=== ULTIMOS SERVICIOS REALIZADOS ==="
sudo mysql -u$(grep DB_USERNAME .env | cut -d= -f2) -p$(grep DB_PASSWORD .env | cut -d= -f2) $(grep DB_DATABASE .env | cut -d= -f2) -e "SELECT id, precio_cobrado, fecha_realizacion FROM servicio_realizado ORDER BY fecha_realizacion DESC LIMIT 5;"
