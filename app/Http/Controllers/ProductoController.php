<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Ajuste;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $buscar = $request->get('buscar');

        $productos = Producto::with('categoria')
            ->when($buscar, function ($query, $buscar) {
                return $query->where('nombre', 'LIKE', "%{$buscar}%")
                             ->orWhere('descripcion', 'LIKE', "%{$buscar}%");
            })
            ->latest()
            ->paginate(10);

        return view('admin.productos.index', compact('productos', 'buscar'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Obtenemos las categorías activas para seleccionarlas en el formulario
        $categorias = Categoria::where('estado', 'Activo')->get();
        return view('admin.productos.create', compact('categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre'       => 'required|string|max:255|unique:productos,nombre',
            'descripcion'  => 'nullable|string',
            'precio'       => 'required|numeric|min:0',
            'stock'        => 'required|integer|min:0',
            'categoria_id' => 'required|exists:categorias,id',
            'imagen'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'estado'       => 'required|in:Activo,Inactivo',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->nombre);

        // Manejo de la subida de la imagen
        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        Producto::create($data);

        return redirect()->route('admin.productos.index')->with('mensaje', 'Producto creado exitosamente.')->with('icono', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $producto = Producto::with('categoria')->findOrFail($id);
        return view('admin.productos.show', compact('producto'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $producto = Producto::findOrFail($id);
        $categorias = Categoria::where('estado', 'Activo')->get();

        return view('admin.productos.edit', compact('producto', 'categorias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);

        $request->validate([
            'nombre'       => 'required|string|max:255|unique:productos,nombre,' . $producto->id,
            'descripcion'  => 'nullable|string',
            'precio'       => 'required|numeric|min:0',
            'stock'        => 'required|integer|min:0',
            'categoria_id' => 'required|exists:categorias,id',
            'imagen'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'estado'       => 'required|in:Activo,Inactivo',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->nombre);

        // Manejo de la imagen en actualización
        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe físicamente
            if ($producto->imagen && Storage::disk('public')->exists($producto->imagen)) {
                Storage::disk('public')->delete($producto->imagen);
            }

            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $producto->update($data);

        return redirect()->route('admin.productos.index')
                    ->with('mensaje', 'Producto actualizado exitosamente.')
                    ->with('icono', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);

        // Eliminar la imagen del almacenamiento si existe
        if ($producto->imagen && Storage::disk('public')->exists($producto->imagen)) {
            Storage::disk('public')->delete($producto->imagen);
        }

        $producto->delete();

        return redirect()->route('admin.productos.index')
                ->with('mensaje', 'Producto eliminado exitosamente.')
                ->with('icono', 'success');
    }
}
