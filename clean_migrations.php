use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

Schema::dropIfExists('herramienta_entregas');
Schema::dropIfExists('herramienta_incidentes');
Schema::dropIfExists('notificaciones_sistema');

DB::table('migrations')->where('migration', 'like', '%herramienta_entregas%')->delete();
DB::table('migrations')->where('migration', 'like', '%herramienta_incidentes%')->delete();
DB::table('migrations')->where('migration', 'like', '%notificaciones_sistema%')->delete();

echo "Tablas eliminadas y migraciones borradas\n";
