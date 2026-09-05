<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Storage;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');
        $usuarios = User::where('name', 'like', '%' . $buscar . '%')
                        ->orWhere('email', 'like', '%' . $buscar . '%')
                        ->paginate(10)
                        ->withQueryString();

        return view('admin.usuarios.index', compact('usuarios', 'buscar'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.usuarios.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validación de los datos
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'estado' => 'required|in:Activo,Inactivo',
            'foto_perfil' => 'nullable|image|max:2048', // Max 2MB
            'rol' => 'required'
        ]);

        // 2. Manejo de la foto
        if ($request->hasFile('foto_perfil')) {
            $path = $request->file('foto_perfil')->store('usuarios', 'public');
            $validated['foto_perfil'] = $path;
        }

        // 3. Crear usuario (Hashear contraseña)
        $validated['password'] = bcrypt($validated['password']);
        $user = User::create($validated);

        // 4. Asignar rol (Spatie)
        $user->assignRole($request->rol);

        return redirect()->route('admin.usuarios.index')->with([
            'mensaje' => 'Usuario registrado con éxito',
            'icono'   => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $usuario = User::findOrFail($id);
        return view('admin.usuarios.show', compact('usuario'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $usuario = User::findOrFail($id);
        $roles = Role::all();

        return view('admin.usuarios.edit', compact('usuario', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $usuario = User::findOrFail($id);

        // 1. Validaciones
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $usuario->id,
            'rol' => 'required|string|exists:roles,name',
            'foto_perfil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'estado' => 'required|in:Activo,Inactivo',
            // Validamos la contraseña solo si el usuario decide cambiarla
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // 2. Manejo de la foto
        if ($request->hasFile('foto_perfil')) {
            if ($usuario->foto_perfil) {
                Storage::disk('public')->delete($usuario->foto_perfil);
            }
            $path = $request->file('foto_perfil')->store('usuarios', 'public');
            $usuario->foto_perfil = $path;
        }

        // 3. Actualizar datos (Incluimos la foto si fue actualizada)
        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->estado = $request->estado;

        // Solo actualizar contraseña si viene en el request
        if ($request->filled('password')) {
            $usuario->password = bcrypt($request->password);
        }

        $usuario->save();

        // 4. Sincronizar rol
        $usuario->syncRoles([$request->rol]);

        return redirect()->route('admin.usuarios.index')->with([
            'mensaje' => 'Usuario actualizado correctamente',
            'icono' => 'success'
        ]);
    }

    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $usuario = User::findOrFail($id);
        if ($usuario->hasRole('SUPER ADMIN')) {
            return redirect()->route('admin.usuarios.index')->with([
                'mensaje' => 'No puedes eliminar a un Super Admin',
                'icono' => 'error'
            ]);
        }
        $usuario->delete();
        return redirect()->route('admin.usuarios.index')->with([
            'mensaje' => 'Usuario eliminado correctamente',
            'icono' => 'success'
        ]);
    }
}
