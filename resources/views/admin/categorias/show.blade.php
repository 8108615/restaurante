<x-layouts::app title="Detalle de Categoría">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Detalles de la Categoría</flux:heading>
        <br>
        <flux:separator variant="subtle" />
    </div>

    <div class="max-w-2xl bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg p-6 shadow-sm space-y-6">
        <div>
            <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Nombre de la Categoría</h3>
            <p class="text-lg font-bold text-gray-900 dark:text-gray-100 mt-1">{{ $categoria->nombre }}</p>
        </div>

        <div>
            <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Descripción</h3>
            <p class="text-sm text-gray-700 dark:text-gray-300 mt-1">{{ $categoria->descripcion ?? 'Sin descripción registrada.' }}</p>
        </div>

        <div>
            <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Estado</h3>
            <div class="mt-1">
                @if ($categoria->estado == 'Activo')
                    <span class="px-2.5 py-1 bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300 text-xs font-semibold rounded-full">Activo</span>
                @else
                    <span class="px-2.5 py-1 bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300 text-xs font-semibold rounded-full">Inactivo</span>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200 dark:border-zinc-700 text-xs text-gray-500 dark:text-gray-400">
            <div>
                <span class="font-semibold">Creado el:</span> {{ $categoria->created_at->format('d/m/Y H:i') }}
            </div>
            <div>
                <span class="font-semibold">Última actualización:</span> {{ $categoria->updated_at->format('d/m/Y H:i') }}
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-zinc-700">
            <a href="{{ route('admin.categorias.index') }}"
                class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition text-sm">
                Volver al listado
            </a>
            <a href="{{ route('admin.categorias.edit', $categoria->id) }}"
                class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-lg transition text-sm flex items-center gap-2">
                <i class="fas fa-pencil-alt"></i> Editar Categoría
            </a>
        </div>
    </div>
</x-layouts::app>