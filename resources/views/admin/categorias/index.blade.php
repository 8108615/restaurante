<x-layouts::app title="Listado de Categorías">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Categorías de productos</flux:heading>
        <br>
        <flux:separator variant="subtle" />
    </div>

    <div class="flex gap-4">
        <div class="flex-1">
            <form action="{{ route('admin.categorias.index') }}" method="GET" class="flex gap-2 w-1/2">
                <div class="flex-1">
                    <flux:input name="buscar" type="text" icon="magnifying-glass" placeholder="Buscar por nombre o descripción..."
                        value="{{ request('buscar') }}" class="transition-all duration-200" />
                </div>
                <button type="submit"
                    class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                    <i class="fas fa-search"></i> Buscar
                </button>
                @if (request('buscar'))
                    <a href="{{ route('admin.categorias.index') }}"
                        class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                        <i class="fas fa-trash"></i> Limpiar
                    </a>
                @endif
            </form>
        </div>

        <div class="flex-1 justify-end flex">
            {{-- Solo se muestra si tiene permiso para ver el formulario de creación de categoría --}}

            <a href="{{ route('admin.categorias.create') }}"
                class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                <i class="fas fa-plus mr-2"></i> Crear nuevo
            </a>

        </div>
    </div>

    @if (request('buscar'))
        <div class="mt-4 p-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg">
            <p class="text-xl text-gray-700 dark:text-gray-300">
                <i class="fas fa-search mr-2"></i>
                Se {{ $categorias->total() == 1 ? 'encontró' : 'encontraron' }}
                <span class="font-semibold text-blue-600 dark:text-blue-400">{{ $categorias->total() }}</span>
                {{ $categorias->total() == 1 ? 'resultado' : 'resultados' }}
                con la búsqueda: <span class="font-semibold">"{{ request('buscar') }}"</span>
            </p>
        </div>
    @endif

    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 mt-6">
        <table class="min-w-full border-collapse">
            <thead class="bg-gray-50 dark:bg-zinc-900 text-center">
                <tr>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nro</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nombre</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Descripción</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-zinc-800">
                @forelse ($categorias as $categoria)
                    <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition">
                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 text-center">
                            {{ $loop->iteration }}
                        </td>
                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 font-semibold text-center">
                            {{ $categoria->nombre }}
                        </td>
                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 text-sm text-gray-600 dark:text-gray-300 text-center max-w-xs truncate">
                            {{ $categoria->descripcion ?? 'Sin descripción' }}
                        </td>
                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-center">
                            @if ($categoria->estado == 'Activo')
                                <span class="px-2 py-1 bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300 text-xs font-semibold rounded-full">Activo</span>
                            @else
                                <span class="px-2 py-1 bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300 text-xs font-semibold rounded-full">Inactivo</span>
                            @endif
                        </td>
                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-center">
                            <div class="flex justify-center gap-2">

                                {{-- Botón Ver --}}

                                <a href="{{ route('admin.categorias.show', $categoria->id) }}"
                                    class="inline-flex items-center px-3 py-1.5 bg-gray-500 hover:bg-gray-600 text-white text-xs font-semibold rounded transition">
                                    <i class="fas fa-eye mr-1"></i> Ver
                                </a>


                                {{-- Botón Editar --}}

                                <a href="{{ route('admin.categorias.edit', $categoria->id) }}"
                                    class="inline-flex items-center px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white text-xs font-semibold rounded transition">
                                    <i class="fas fa-pencil-alt mr-1"></i> Editar
                                </a>


                                {{-- Botón Eliminar --}}
                                <form action="{{ url('/admin/categoria/' . $categoria->id) }}" method="post" id="formCategoria{{ $categoria->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 cursor-pointer text-white text-xs font-semibold rounded transition"
                                        onclick="confirmarEliminacion{{ $categoria->id }}(event)">
                                        <i class="fas fa-trash-alt mr-2"></i> Eliminar
                                    </button>
                                </form>

                                <script>
                                    function confirmarEliminacion{{ $categoria->id }}(event) {
                                        event.preventDefault();
                                        Swal.fire({
                                            title: '¿Desea eliminar la categoría? ' + '{{ $categoria->nombre }}',
                                            icon: 'question',
                                            showDenyButton: true,
                                            confirmButtonText: 'Eliminar',
                                            confirmButtonColor: '#a5161d',
                                            denyButtonColor: '#270a0a',
                                            denyButtonText: 'Cancelar',
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                document.getElementById('formCategoria{{ $categoria->id }}').submit();
                                            }
                                        });
                                    }
                                </script>


                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                            No se encontraron categorías registradas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($categorias->hasPages())
        <div class="px-3 mt-4 flex justify-between items-center">
            <div class="text-gray-600 dark:text-gray-400 text-sm">
                Mostrando <span class="font-semibold">{{ $categorias->firstItem() }}</span>
                al <span class="font-semibold">{{ $categorias->lastItem() }}</span>
                de <span class="font-semibold">{{ $categorias->total() }}</span> resultados.
            </div>
            <div>
                {{ $categorias->links() }}
            </div>
        </div>
    @endif
</x-layouts::app>
