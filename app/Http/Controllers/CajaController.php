<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CajaController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->get('buscar');

        // Buscar si hay una caja abierta actualmente
        $cajaAbierta = Caja::where('estado', 'abierto')->latest()->first();

        // Historial con filtro de búsqueda opcional
        $cajasHistorial = Caja::when($buscar, function ($query, $buscar) {
                return $query->where('estado', 'like', "%{$buscar}%")
                             ->orWhereHas('user', function ($q) use ($buscar) {
                                 $q->where('name', 'like', "%{$buscar}%");
                             });
            })
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->appends(['buscar' => $buscar]);

        return view('admin.cajas.index', compact('cajaAbierta', 'cajasHistorial', 'buscar'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'saldo_inicial' => 'required|numeric|min:0',
        ]);

        $cajaAbierta = Caja::where('estado', 'abierto')->first();
        if ($cajaAbierta) {
            return redirect()->back()->with('error', 'Ya existe una caja abierta.');
        }

        Caja::create([
            'user_id' => Auth::id(),
            'saldo_inicial' => $request->saldo_inicial,
            'fecha_apertura' => Carbon::now(),
            'estado' => 'abierto',
        ]);

        return redirect()->route('admin.cajas.index')->with('success', 'Caja abierta exitosamente.');
    }

    public function show($id)
    {
        // Buscamos la caja con su cajero y sus ventas (incluyendo detalles/productos y cliente si aplica)
        $caja = Caja::with(['user', 'ventas.detalles.producto'])->findOrFail($id);

        // Calculamos el total vendido en este turno sumando las ventas
        $totalVendido = $caja->ventas->sum('total'); // o el campo que uses para el total de la venta

        return view('admin.cajas.show', compact('caja', 'totalVendido'));
    }

    public function cerrar(Request $request, $id)
    {
        $caja = Caja::findOrFail($id);

        if ($caja->estado !== 'abierto') {
            return redirect()->back()->with('error', 'Esta caja ya está cerrada.');
        }

        $totalVentas = Venta::where('created_at', '>=', $caja->fecha_apertura)
                            ->where('estado', 'Completado')
                            ->sum('total');

        $saldoFinal = $caja->saldo_inicial + $totalVentas;

        $caja->update([
            'total_ventas' => $totalVentas,
            'saldo_final' => $saldoFinal,
            'fecha_cierre' => Carbon::now(),
            'estado' => 'cerrado',
        ]);

        return redirect()->route('admin.cajas.index')->with('success', 'Caja cerrada correctamente.');
    }

    public function destroy($id)
    {
        $caja = Caja::findOrFail($id);
        $caja->delete();

        return redirect()->route('admin.cajas.index')->with('success', 'Registro de caja eliminado correctamente.');
    }

    public function reporte($id)
    {
        $caja = \App\Models\Caja::with('user', 'ventas.detalles.producto')->findOrFail($id);

        // Calcular el total vendido en esta caja
        $totalVendido = $caja->ventas->where('estado', 'Completado')->sum('total');

        // Símbolo de divisa predeterminado (puedes cambiarlo si usas tu modelo Ajuste)
        $simboloDivisa = 'Bs';

        return view('admin.cajas.pdf', compact('caja', 'totalVendido', 'simboloDivisa'));
    }
}
