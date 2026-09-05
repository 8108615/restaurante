<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $buscar = $request->get('buscar');

        $categorias = Categoria::when($buscar, function ($query, $buscar) {
                return $query->where('nombre', 'LIKE', "%{$buscar}%")
                             ->orWhere('descripcion', 'LIKE', "%{$buscar}%");
            })
            ->latest()
            ->paginate(10);

        return view('admin.categorias.index', compact('categorias', 'buscar'));
    }

    public function create()
    {
        return view('admin.categorias.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:categorias,nombre',
            'descripcion' => 'nullable|string',
            'estado' => 'required|in:Activo,Inactivo',
        ]);

        Categoria::create($request->all());

        return redirect()->route('admin.categorias.index')->with('mensaje', 'Categoría creada exitosamente.')->with('icono', 'success');
    }

    public function show($id)
    {
        $categoria = Categoria::findOrFail($id);
        return view('admin.categorias.show', compact('categoria'));
    }

    public function edit($id)
    {
        $categoria = Categoria::findOrFail($id);
        return view('admin.categorias.edit', compact('categoria'));
    }

    public function update(Request $request, $id)
    {
        $categoria = Categoria::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255|unique:categorias,nombre,' . $categoria->id,
            'descripcion' => 'nullable|string',
            'estado' => 'required|in:Activo,Inactivo',
        ]);

        $categoria->update($request->all());

        return redirect()->route('admin.categorias.index')
                    ->with('mensaje', 'Categoría actualizada exitosamente.')
                    ->with('icono', 'success');
    }

    public function destroy($id)
    {
        $categoria = Categoria::findOrFail($id);

        // Opcional: Aquí luego podemos validar si tiene productos asociados antes de eliminar
        $categoria->delete();

        return redirect()->route('admin.categorias.index')->with('mensaje', 'Categoría eliminada exitosamente.')->with('icono', 'success');

    }
}
