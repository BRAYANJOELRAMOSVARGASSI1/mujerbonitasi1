<?php

namespace App\Modules\P3_GestionInventarioHerramientas\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\P3_GestionInventarioHerramientas\Models\Producto;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $productos = Producto::query()
            ->when($request->filled('buscar'), function ($q) use ($request) {
                $q->where(function ($sub) use ($request) {
                    $sub->where('nombre', 'like', "%{$request->buscar}%")
                        ->orWhere('marca', 'like', "%{$request->buscar}%");
                });
            })
            ->when($request->filled('categoria'), fn($q) => $q->where('categoria', $request->categoria))
            ->when($request->filled('alerta'), function ($q) use ($request) {
                if ($request->alerta === 'bajo') {
                    $q->whereColumn('stock_actual', '<=', 'stock_minimo')->where('stock_actual', '>', 0);
                } elseif ($request->alerta === 'critico') {
                    $q->where('stock_actual', 0);
                }
            })
            ->orderBy('nombre')
            ->paginate(12)
            ->withQueryString();

        $categorias = Producto::CATEGORIAS;
        return view('modules.inventario.productos.index', compact('productos', 'categorias'));
    }

    public function create()
    {
        $categorias = Producto::CATEGORIAS;
        $unidades   = Producto::UNIDADES;
        return view('modules.inventario.productos.create', compact('categorias', 'unidades'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'        => ['required', 'string', 'max:150'],
            'descripcion'   => ['nullable', 'string', 'max:1000'],
            'categoria'     => ['required', 'string', 'max:80'],
            'marca'         => ['nullable', 'string', 'max:100'],
            'precio_compra' => ['required', 'numeric', 'min:0'],
            'precio_venta'  => ['nullable', 'numeric', 'min:0'],
            'stock_actual'  => ['required', 'integer', 'min:0'],
            'stock_minimo'  => ['required', 'integer', 'min:0'],
            'unidad_medida' => ['nullable', 'string', 'max:30'],
        ]);

        $producto = Producto::create($data);

        ActivityLog::create([
            'user_id'    => Auth::id(),
            'action'     => 'Registro de producto',
            'description'=> "Producto registrado: {$producto->nombre} (Cat: {$producto->categoria}, Stock: {$producto->stock_actual})",
            'ip_address' => $request->ip() ?? 'No disponible',
            'browser'    => $request->header('user-agent') ?? 'No disponible',
        ]);

        return redirect()->route('productos.index')->with('status', 'Producto registrado correctamente.');
    }

    public function show(Producto $producto)
    {
        return view('modules.inventario.productos.show', compact('producto'));
    }

    public function edit(Producto $producto)
    {
        $categorias = Producto::CATEGORIAS;
        $unidades   = Producto::UNIDADES;
        return view('modules.inventario.productos.edit', compact('producto', 'categorias', 'unidades'));
    }

    public function update(Request $request, Producto $producto)
    {
        $data = $request->validate([
            'nombre'        => ['required', 'string', 'max:150'],
            'descripcion'   => ['nullable', 'string', 'max:1000'],
            'categoria'     => ['required', 'string', 'max:80'],
            'marca'         => ['nullable', 'string', 'max:100'],
            'precio_compra' => ['required', 'numeric', 'min:0'],
            'precio_venta'  => ['nullable', 'numeric', 'min:0'],
            'stock_actual'  => ['required', 'integer', 'min:0'],
            'stock_minimo'  => ['required', 'integer', 'min:0'],
            'unidad_medida' => ['nullable', 'string', 'max:30'],
            'estado'        => ['nullable', 'in:activo,inactivo'],
        ]);

        $producto->update($data);

        ActivityLog::create([
            'user_id'    => Auth::id(),
            'action'     => 'Actualización de producto',
            'description'=> "Producto actualizado: {$producto->nombre} (Stock: {$producto->stock_actual})",
            'ip_address' => $request->ip() ?? 'No disponible',
            'browser'    => $request->header('user-agent') ?? 'No disponible',
        ]);

        return redirect()->route('productos.index')->with('status', 'Producto actualizado correctamente.');
    }

    public function destroy(Request $request, Producto $producto)
    {
        $nombre = $producto->nombre;
        $producto->delete();

        ActivityLog::create([
            'user_id'    => Auth::id(),
            'action'     => 'Eliminación de producto',
            'description'=> "Producto eliminado: {$nombre}",
            'ip_address' => $request->ip() ?? 'No disponible',
            'browser'    => $request->header('user-agent') ?? 'No disponible',
        ]);

        return redirect()->route('productos.index')->with('status', 'Producto eliminado correctamente.');
    }
}
