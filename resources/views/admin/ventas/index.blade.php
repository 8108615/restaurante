<x-layouts::app title="Historial de Ventas">
    <div class="relative mb-4 w-full flex justify-between items-center">
        <flux:heading size="xl" level="1">
            <i class="fas fa-history mr-2"></i> Historial de Ventas
        </flux:heading>

        <a href="{{ route('admin.ventas.create') }}"
            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition text-sm flex items-center gap-2 shadow-sm">
            <i class="fas fa-cash-register"></i> Ir al POS / Caja
        </a>
    </div>

    <flux:separator variant="subtle" class="mb-6" />

    <!-- Contenedor Principal -->
    <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg shadow-sm p-4 space-y-4">

        <!-- Barra de Búsqueda -->
        <form method="GET" action="{{ route('admin.ventas.index') }}" class="flex gap-2">
            <div class="relative flex-1 md:w-1/3">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" name="buscar" value="{{ $buscar ?? '' }}" placeholder="Buscar por ID o método de pago..."
                    class="w-full pl-10 pr-4 py-2 rounded-lg border-gray-300 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-900 text-gray-900 dark:text-gray-100 text-sm">
            </div>
            <button type="submit" class="px-4 py-2 bg-gray-700 hover:bg-gray-800 text-white text-sm font-semibold rounded-lg transition">
                Buscar
            </button>
            @if(isset($buscar) && $buscar)
                <a href="{{ route('admin.ventas.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-zinc-700 text-gray-700 dark:text-gray-300 text-sm font-semibold rounded-lg flex items-center transition">
                    Limpiar
                </a>
            @endif
        </form>

        <!-- Tabla de Ventas -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-zinc-700 text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-zinc-900">
                        <th class="p-3">ID</th>
                        <th class="p-3">Fecha y Hora</th>
                        <th class="p-3">Vendedor</th>
                        <th class="p-3">Método Pago</th>
                        <th class="p-3">Total</th>
                        <th class="p-3">Estado</th>
                        <th class="p-3 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-zinc-700 text-gray-800 dark:text-gray-200">
                    @forelse($ventas as $venta)
                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-900/50 transition">
                            <td class="p-3 font-bold">#{{ $venta->id }}</td>
                            <td class="p-3 text-xs text-gray-500 dark:text-gray-400">{{ $venta->created_at->format('d/m/Y H:i') }}</td>
                            <td class="p-3">{{ $venta->user->name ?? 'N/A' }}</td>
                            <td class="p-3">
                                <span class="px-2 py-1 rounded text-xs bg-gray-100 dark:bg-zinc-700 font-medium">
                                    {{ $venta->metodo_pago }}
                                </span>
                            </td>
                            <td class="p-3 font-extrabold text-emerald-600 dark:text-emerald-400">
                                {{ $simboloDivisa ?? 'Bs' }} {{ number_format($venta->total, 2) }}
                            </td>
                            <td class="p-3">
                                @if($venta->estado === 'Completado')
                                    <span class="px-2.5 py-0.5 rounded-full text-xs bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400 font-semibold">Completado</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-xs bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 font-semibold">Anulado</span>
                                @endif
                            </td>
                            <td class="p-3 text-center space-x-3">
                                <!-- Ver Comprobante -->
                                <a href="{{ route('admin.ventas.show', $venta->id) }}" class="text-blue-500 hover:text-blue-700 transition" title="Ver Comprobante">
                                    <i class="fas fa-eye text-base"></i>
                                </a>

                                <a href="{{ route('admin.ventas.show', $venta->id) }}?print=true" target="_blank" class="text-emerald-500 hover:text-emerald-700 transition" title="Imprimir Ticket Rápido">
                                    <i class="fas fa-print text-base"></i>
                                </a>

                                <!-- Anular Venta -->
                                @if($venta->estado === 'Completado')
                                    <form action="{{ route('admin.ventas.destroy', $venta->id) }}" method="POST" class="inline form-anular">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 transition cursor-pointer" title="Anular Venta" onclick="return confirm('¿Estás seguro de anular esta venta? Se repondrá el stock.')">
                                            <i class="fas fa-ban text-base"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-gray-500 dark:text-gray-400">
                                <i class="fas fa-folder-open fa-2x mb-2 block text-gray-400"></i>
                                No hay ventas registradas todavía.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="mt-4 pt-2 border-t border-gray-200 dark:border-zinc-700">
            {{ $ventas->links() }}
        </div>

    </div>
</x-layouts::app>
