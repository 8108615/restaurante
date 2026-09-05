<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{

    public function index(Request $request)
    {
        $buscar = $request->input('buscar');

        $roles = Role::where('name', 'like', '%' . $buscar . '%')
                    ->paginate(10)
                    ->withQueryString();

        return view('admin.roles.index', compact('roles', 'buscar'));
    }

    public function create()
    {
        return view('admin.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ]);

        $rol = new Role();
        $rol->name = $request->name;
        $rol->save();

        return redirect()->route('admin.roles.index')
            ->with('mensaje', 'Rol guardado correctamente')
            ->with('icono', 'success');
    }

    public function show(string $id)
    {
        $rol = Role::find($id);
        return view('admin.roles.show', compact('rol'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $rol = Role::find($id);
        return view('admin.roles.edit', compact('rol'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
        ]);

        $rol = Role::find($id);
        $rol->name = $request->name;
        $rol->save();

        return redirect()->route('admin.roles.index')
            ->with('mensaje', 'Rol actualizado correctamente')
            ->with('icono', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $rol = Role::find($id);
        $rol->delete();

        return redirect()->route('admin.roles.index')
            ->with('mensaje', 'Rol eliminado correctamente')
            ->with('icono', 'success');
    }

    public function permisos(string $id)
    {
        $rol = Role::findOrFail($id);

        // Agrupamos los permisos por el módulo al que pertenecen según su nombre o texto descriptivo
        $permisos = Permission::all()->groupBy(function($permission) {
            $nombre = strtolower($permission->name);

            if (str_contains($nombre, 'rol')) return 'ROLES';
            if (str_contains($nombre, 'usuario')) return 'USUARIOS';
            if (str_contains($nombre, 'categoria')) return 'CATEGORIAS';
            if (str_contains($nombre, 'producto') || str_contains($nombre, 'stock') || str_contains($nombre, 'barras')) return 'PRODUCTOS';
            if (str_contains($nombre, 'proveedor')) return 'PROVEEDORES';
            if (str_contains($nombre, 'cliente')) return 'CLIENTES';
            if (str_contains($nombre, 'compra')) return 'COMPRAS';
            if (str_contains($nombre, 'venta') || str_contains($nombre, 'ticket')) return 'VENTAS';
            if (str_contains($nombre, 'caja')) return 'CAJAS';
            if (str_contains($nombre, 'ajuste')) return 'AJUSTES';
            if (str_contains($nombre, 'dashboard')) return 'DASHBOARD';

            return 'GENERAL';
        });

        return view('admin.roles.permisos', compact('rol', 'permisos'));
    }

    public function guardarPermisos(Request $request, string $id)
    {
        $rol = Role::findOrFail($id);

        // Sincroniza los permisos seleccionados del formulario
        $rol->syncPermissions($request->input('permisos', []));

        return redirect()->route('admin.roles.index')
            ->with('mensaje', 'Permisos asignados correctamente')
            ->with('icono', 'success');
    }
}
