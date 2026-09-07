<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VentaController extends Controller
{
    /**
     * Display a listing of the resource. (Historial de Ventas)
     */
    public function index(Request $request)
    {
        $buscar = $request->get('buscar');

        $ventas = Venta::with('user', 'detalles.producto')
            ->when($buscar, function ($query, $buscar) {
                return $query->where('id', 'LIKE', "%{$buscar}%")
                             ->orWhere('metodo_pago', 'LIKE', "%{$buscar}%");
            })
            ->latest()
            ->paginate(10);

        return view('admin.ventas.index', compact('ventas', 'buscar'));
    }

    /**
     * Show the form for creating a new resource. (La Pantalla del POS / Caja)
     */
    public function create()
    {
        // Obtenemos categorías y productos activos con stock disponible
        $categorias = Categoria::where('estado', 'Activo')->get();
        $productos = Producto::where('estado', 'Activo')->where('stock', '>', 0)->get();

        return view('admin.ventas.create', compact('categorias', 'productos'));
    }

    /**
     * Store a newly created resource in storage. (Procesar y guardar la venta del POS)
     */
    public function store(Request $request)
    {
        $request->validate([
            'productos' => 'required|array|min:1',
            'productos.*.id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'metodo_pago' => 'required|string',
            'total' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // 1. Crear la cabecera de la venta
            $venta = Venta::create([
                'user_id' => Auth::id(),
                'total' => $request->total,
                'metodo_pago' => $request->metodo_pago,
                'monto_pagado' => $request->input('monto_pagado', $request->total),
                'cambio' => $request->input('cambio', 0),
                'estado' => 'Completado'
            ]);

            // 2. Registrar los detalles y descontar stock
            foreach ($request->productos as $item) {
                $producto = Producto::findOrFail($item['id']);

                // Validar stock suficiente
                if ($producto->stock < $item['cantidad']) {
                    throw new \Exception("Stock insuficiente para el producto: {$producto->nombre}");
                }

                $subtotal = $item['cantidad'] * $producto->precio;

                DetalleVenta::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $producto->id,
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $producto->precio,
                    'subtotal' => $subtotal,
                ]);

                // Descontar el stock actual
                $producto->decrement('stock', $item['cantidad']);
            }

            DB::commit();

            // Cargar relaciones para el ticket
            $venta->load('detalles.producto');

            // Obtener datos de la empresa/ajustes (Si tienes un modelo Ajuste, puedes usarlo aquí.
            // Si no, puedes cambiar estos valores por los tuyos temporalmente)
            // Ejemplo si usas una tabla o clase de configuraciones:
            // $config = \App\Models\Ajuste::first();

            $ajuste = \App\Models\Ajuste::first();

            $empresaData = [
                'nombre'    => $ajuste->nombre ?? 'ERICK SYSTEMS',
                'direccion' => $ajuste->direccion ?? 'Santa Cruz, Bolivia',
                'celular'   => $ajuste->telefono ?? '',
                'logo'      => ($ajuste && $ajuste->logo) ? asset('storage/' . $ajuste->logo) : null
            ];

            return response()->json([
                'success' => true,
                'mensaje' => '¡Venta registrada exitosamente!',
                'venta_id' => $venta->id,
                'venta' => [
                    'id' => $venta->id,
                    'fecha' => $venta->created_at->format('d/m/Y H:i'),
                    'metodo_pago' => $venta->metodo_pago,
                    'total' => $venta->total,
                    'monto_pagado' => $venta->monto_pagado,
                    'cambio' => $venta->cambio,
                    'detalles' => $venta->detalles->map(function($d) {
                        return [
                            'nombre' => $d->producto->nombre ?? 'Producto',
                            'cantidad' => $d->cantidad,
                            'precio' => $d->precio_unitario,
                            'subtotal' => $d->subtotal
                        ];
                    })
                ],
                'empresa' => $empresaData
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'mensaje' => 'Error al procesar la venta: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Display the specified resource. (Comprobante / Detalle de una venta)
     */
    public function show($id)
    {
        // Cargamos la venta con su usuario y los detalles junto al producto
        $venta = Venta::with('user', 'detalles.producto')->findOrFail($id);

        // Obtenemos los ajustes de la empresa para el diseño y el ticket
        $ajuste = \App\Models\Ajuste::first();

        return view('admin.ventas.show', compact('venta', 'ajuste'));
    }

    /**
     * Remove the specified resource from storage. (Anular venta)
     */
    public function destroy($id)
    {
        $venta = Venta::findOrFail($id);

        // Opcional: Reintegrar stock si se anula
        DB::transaction(function () use ($venta) {
            foreach ($venta->detalles as $detalle) {
                $detalle->producto->increment('stock', $detalle->cantidad);
            }
            $venta->update(['estado' => 'Anulado']);
        });

        return redirect()->route('admin.ventas.index')
            ->with('mensaje', 'Venta anulada correctamente.')
            ->with('icono', 'success');
    }
}
