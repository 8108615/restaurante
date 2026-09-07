<x-layouts::app title="Detalles del Producto">
    <div class="relative mb-6 w-full flex justify-between items-center">
        <flux:heading size="xl" level="1">
            <i class="fas fa-eye mr-2"></i> Detalles del Producto / Plato
        </flux:heading>

        <div class="flex gap-2">
            <a href="{{ route('admin.productos.edit', $producto->id) }}"
                class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-lg transition text-sm flex items-center gap-2">
                <i class="fas fa-edit"></i> Editar
            </a>
            <a href="{{ route('admin.productos.index') }}"
                class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition text-sm flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <flux:separator variant="subtle" class="mb-6" />

    <div class="max-w-4xl bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg p-6 shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">

            <!-- Columna de la Imagen -->
            <div class="flex flex-col items-center justify-center bg-gray-50 dark:bg-zinc-900 p-4 rounded-lg border border-gray-200 dark:border-zinc-700">
                @if ($producto->imagen)
                    <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}" class="h-48 w-48 object-cover rounded-lg shadow-md border border-gray-300 dark:border-zinc-700">
                @else
                    <div class="h-48 w-48 flex flex-col items-center justify-center bg-gray-200 dark:bg-zinc-800 rounded-lg text-gray-400">
                        <i class="fas fa-image fa-3x mb-2"></i>
                        <span class="text-xs">Sin imagen</span>
                    </div>
                @endif
                <span class="mt-3 text-xs text-gray-500 dark:text-gray-400 font-mono">Slug: {{ $producto->slug }}</span>
            </div>

            <!-- Columna de Datos Generales -->
            <div class="md:col-span-2 space-y-4">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-blue-600 dark:text-blue-400">
                        {{ $producto->categoria->nombre ?? 'Sin categoría' }}
                    </span>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-1">
                        {{ $producto->nombre }}
                    </h2>
                </div>

                <div class="grid grid-cols-2 gap-4 py-3 border-t border-b border-gray-200 dark:border-zinc-700">
                    <div>
                        <span class="block text-xs text-gray-500 dark:text-gray-400">Precio de Venta</span>
                        <span class="text-xl font-extrabold text-emerald-600 dark:text-emerald-400">
                            {{ $simboloDivisa ?? '$' }} {{ number_format($producto->precio, 2) }}
                        </span>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500 dark:text-gray-400">Stock Disponible</span>
                        <span class="text-lg font-bold text-gray-800 dark:text-gray-200">
                            {{ $producto->stock }} unidades
                        </span>
                    </div>
                </div>

                <div>
                    <span class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Estado del Producto</span>
                    @if($producto->estado == 'Activo')
                        <span class="px-3 py-1 inline-flex items-center text-xs font-bold rounded-full bg-emerald-500 text-white shadow-sm">
                            <i class="fas fa-check-circle mr-1.5"></i> Activo
                        </span>
                    @else
                        <span class="px-3 py-1 inline-flex items-center text-xs font-bold rounded-full bg-red-500 text-white shadow-sm">
                            <i class="fas fa-times-circle mr-1.5"></i> Inactivo
                        </span>
                    @endif
                </div>

                <div>
                    <span class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Descripción / Detalles</span>
                    <p class="text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-zinc-900 p-3 rounded-lg border border-gray-200 dark:border-zinc-700 min-h-[80px]">
                        {{ $producto->descripcion ?: 'No hay una descripción registrada para este producto.' }}
                    </p>
                </div>

                <div class="text-xs text-gray-400 pt-2 flex justify-between">
                    <span>Creado: {{ $producto->created_at?->format('d/m/Y H:i') }}</span>
                    <span>Última actualización: {{ $producto->updated_at?->format('d/m/Y H:i') }}</span>
                </div>
            </div>

        </div>
    </div>
</x-layouts::app>
