<x-layouts::app title="Listado de Usuarios">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Usuarios del sistema</flux:heading>
        <br>
        <flux:separator variant="subtle" />
    </div>
    <div class="flex gap-4">
        <div class="flex-1">
            <form action="{{ route('admin.usuarios.index') }}" method="GET" class="flex gap-2 w-1/2">
                <div class="flex-1">
                    <flux:input name="buscar" type="text" icon="magnifying-glass" placeholder="Buscar por nombre o email..."
                        value="{{ request('buscar') }}" class="transition-all duration-200" />
                </div>
                <button type="submit"
                    class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                    <i class="fas fa-search"></i> Buscar
                </button>
                @if (request('buscar'))
                    <a href="{{ route('admin.usuarios.index') }}"
                        class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                        <i class="fas fa-trash"></i> Limpiar
                    </a>
                @endif
            </form>
        </div>

        <div class="flex-1 justify-end flex">
            <a href="{{ route('admin.usuarios.create') }}"
                class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                <i class="fas fa-plus mr-2"></i> Crear nuevo
            </a>
        </div>
    </div>

    @if (request('buscar'))
        <div class="mt-4 p-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg">
            <p class="text-xl text-gray-700 dark:text-gray-300">
                <i class="fas fa-search mr-2"></i>
                Se {{ $usuarios->total() == 1 ? 'encontró' : 'encontraron' }}
                <span class="font-semibold text-blue-600 dark:text-blue-400">{{ $usuarios->total() }}</span>
                {{ $usuarios->total() == 1 ? 'resultado' : 'resultados' }}
                con la búsqueda: <span class="font-semibold">"{{ request('buscar') }}"</span>
            </p>
        </div>
    @endif

    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 mt-6">
        <table class="min-w-full border-collapse">
            <thead class="bg-gray-50 dark:bg-zinc-900 text-center">
                <tr>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nro</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Foto</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Usuario</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Rol</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-zinc-800">
                @foreach ($usuarios as $usuario)
                    <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition">
                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 text-center">
                            {{ $loop->iteration }}
                        </td>
                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-center">
                            @if ($usuario->foto_perfil)
                                <img src="{{ asset('storage/' . $usuario->foto_perfil) }}" alt="Avatar" class="w-10 h-10 rounded-full object-cover mx-auto shadow">
                            @else
                                <div class="w-10 h-10 rounded-full bg-gray-300 dark:bg-zinc-700 flex items-center justify-center mx-auto text-gray-600 dark:text-gray-300 font-bold text-xs">
                                    {{ strtoupper(substr($usuario->name, 0, 2)) }}
                                </div>
                            @endif
                        </td>
                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                            <div class="font-semibold">{{ $usuario->name }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $usuario->email }}</div>
                        </td>
                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-sm text-center text-gray-900 dark:text-gray-100">
                            @foreach ($usuario->getRoleNames() as $rol)
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300 text-xs font-semibold rounded-full">{{ $rol }}</span>
                            @endforeach
                        </td>
                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-center">
                            @if ($usuario->estado == 'Activo')
                                <span class="px-2 py-1 bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300 text-xs font-semibold rounded-full">Activo</span>
                            @else
                                <span class="px-2 py-1 bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300 text-xs font-semibold rounded-full">Inactivo</span>
                            @endif
                        </td>
                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('admin.usuarios.show', $usuario->id) }}"
                                    class="inline-flex items-center px-3 py-1.5 bg-gray-500 hover:bg-gray-600 text-white text-xs font-semibold rounded transition">
                                    <i class="fas fa-eye mr-1"></i> Ver
                                </a>
                                <a href="{{ route('admin.usuarios.edit', $usuario->id) }}"
                                    class="inline-flex items-center px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white text-xs font-semibold rounded transition">
                                    <i class="fas fa-pencil-alt mr-1"></i> Editar
                                </a>
                                <form action="{{ url('/admin/usuario/' . $usuario->id) }}" method="post" id="formUsuario{{ $usuario->id }}">
                                    @csrf
                                    @method('DELETE')
                                    @php
                                        $esSuperAdmin = $usuario->hasRole('SUPER ADMIN');
                                    @endphp
                                    <button type="submit"
                                        class="inline-flex items-center px-4 py-2 text-white text-xs font-semibold rounded transition
                                            {{ $esSuperAdmin ? 'bg-gray-400 cursor-not-allowed' : 'bg-red-500 hover:bg-red-600 cursor-pointer' }}"
                                        onclick="{{ $esSuperAdmin ? 'event.preventDefault();' : 'confirmarEliminacion' . $usuario->id . '(event)' }}">
                                        <i class="fas fa-trash-alt mr-2"></i> {{ $esSuperAdmin ? 'Protegido' : 'Eliminar' }}
                                    </button>
                                </form>

                                <script>
                                    function confirmarEliminacion{{ $usuario->id }}(event) {
                                        event.preventDefault();
                                        Swal.fire({
                                            title: '¿Desea eliminar este usuario? ' + '{{ $usuario->name }}',
                                            icon: 'question',
                                            showDenyButton: true,
                                            confirmButtonText: 'Eliminar',
                                            confirmButtonColor: '#a5161d',
                                            denyButtonColor: '#270a0a',
                                            denyButtonText: 'Cancelar',
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                document.getElementById('formUsuario{{ $usuario->id }}').submit();
                                            }
                                        });
                                    }
                                </script>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if ($usuarios->hasPages())
        <div class="px-3 mt-4 flex justify-between items-center">
            <div class="text-gray-600 dark:text-gray-400 text-sm">
                Mostrando <span class="font-semibold">{{ $usuarios->firstItem() }}</span>
                al <span class="font-semibold">{{ $usuarios->lastItem() }}</span>
                de <span class="font-semibold">{{ $usuarios->total() }}</span> resultados.
            </div>
            <div>
                {{ $usuarios->links() }}
            </div>
        </div>
    @endif
</x-layouts::app>
