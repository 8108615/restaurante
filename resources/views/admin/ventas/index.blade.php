<x-layouts::app title="Historial de Ventas">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">
            <i class="fas fa-history mr-2"></i> Historial de Ventas
        </flux:heading>
        <br>
        <flux:separator variant="subtle" />
    </div>

    <!-- Barra de Búsqueda y Botón POS -->
    <div class="flex gap-4 items-center">
        <div class="flex-1">
            <form action="{{ route('admin.ventas.index') }}" method="GET" class="flex gap-2 w-1/2">
                <div class="flex-1">
                    <flux:input name="buscar" type="text" icon="magnifying-glass" placeholder="Buscar por ID o método de pago..."
                        value="{{ request('buscar') }}" class="transition-all duration-200" />
                </div>
                <button type="submit"
                    class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                    <i class="fas fa-search"></i> Buscar
                </button>
                @if (request('buscar'))
                    <a href="{{ route('admin.ventas.index') }}"
                        class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                        <i class="fas fa-trash"></i> Limpiar
                    </a>
                @endif
            </form>
        </div>

        <div class="flex-1 justify-end flex">
            <a href="{{ route('admin.ventas.create') }}"
                class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition flex items-center gap-2 shadow-sm">
                <i class="fas fa-cash-register"></i> Ir al POS / Caja
            </a>
        </div>
    </div>

    <!-- Alerta de resultados de búsqueda -->
    @if (request('buscar'))
        <div class="mt-4 p-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg">
            <p class="text-xl text-gray-700 dark:text-gray-300">
                <i class="fas fa-search mr-2"></i>
                Se {{ $ventas->total() == 1 ? 'encontró' : 'encontraron' }}
                <span class="font-semibold text-blue-600 dark:text-blue-400">{{ $ventas->total() }}</span>
                {{ $ventas->total() == 1 ? 'resultado' : 'resultados' }}
                con la búsqueda: <span class="font-semibold">"{{ request('buscar') }}"</span>
            </p>
        </div>
    @endif

    <!-- Tabla de Ventas -->
    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 mt-6">
        <table class="min-w-full border-collapse">
            <thead class="bg-gray-50 dark:bg-zinc-900 text-center">
                <tr>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fecha y Hora</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Vendedor</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Método Pago</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-zinc-800">
                @forelse($ventas as $venta)
                    <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition">
                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 font-bold text-center">
                            #{{ $venta->id }}
                        </td>
                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-xs text-gray-500 dark:text-gray-400 text-center">
                            {{ $venta->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 text-center">
                            {{ $venta->user->name ?? 'N/A' }}
                        </td>
                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-center">
                            <span class="px-2 py-1 rounded text-xs bg-gray-100 dark:bg-zinc-700 font-medium text-gray-800 dark:text-gray-200">
                                {{ $venta->metodo_pago }}
                            </span>
                        </td>
                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-sm font-extrabold text-emerald-600 dark:text-emerald-400 text-center">
                            {{ $simboloDivisa ?? 'Bs' }} {{ number_format($venta->total, 2) }}
                        </td>
                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-center">
                            @if($venta->estado === 'Completado')
                                <span class="px-2.5 py-0.5 rounded-full text-xs bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400 font-semibold">Completado</span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-xs bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 font-semibold">Anulado</span>
                            @endif
                        </td>
                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-center">
                            <div class="flex justify-center gap-2">
                                <!-- Ver Comprobante -->
                                <a href="{{ route('admin.ventas.show', $venta->id) }}"
                                    class="inline-flex items-center px-3 py-1.5 bg-gray-500 hover:bg-gray-600 text-white text-xs font-semibold rounded transition" title="Ver Comprobante">
                                    <i class="fas fa-eye mr-1"></i> Ver
                                </a>

                                <!-- Imprimir Ticket Rápido -->
                                <a href="{{ route('admin.ventas.show', $venta->id) }}?print=true" target="_blank"
                                    class="inline-flex items-center px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-semibold rounded transition" title="Imprimir Ticket Rápido">
                                    <i class="fas fa-print mr-1"></i> Ticket
                                </a>

                                <!-- Anular Venta con SweetAlert2 -->
                                @if($venta->estado === 'Completado')
                                    <form action="{{ route('admin.ventas.destroy', $venta->id) }}" method="POST" id="formVenta{{ $venta->id }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center px-3 py-1.5 bg-red-500 hover:bg-red-600 cursor-pointer text-white text-xs font-semibold rounded transition"
                                            onclick="confirmarAnulacion{{ $venta->id }}(event)" title="Anular Venta">
                                            <i class="fas fa-ban mr-1"></i> Anular
                                        </button>
                                    </form>

                                    <script>
                                        function confirmarAnulacion{{ $venta->id }}(event) {
                                            event.preventDefault();
                                            Swal.fire({
                                                title: '¿Desea anular la venta #' + '{{ $venta->id }}? Se repondrá el stock.',
                                                icon: 'question',
                                                showDenyButton: true,
                                                confirmButtonText: 'Anular',
                                                confirmButtonColor: '#a5161d',
                                                denyButtonColor: '#270a0a',
                                                denyButtonText: 'Cancelar',
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    document.getElementById('formVenta{{ $venta->id }}').submit();
                                                }
                                            });
                                        }
                                    </script>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                            <i class="fas fa-folder-open fa-2x mb-2 block text-gray-400"></i>
                            No hay ventas registradas todavía.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación con el contador estándar -->
    @if ($ventas->hasPages())
        <div class="px-3 mt-4 flex justify-between items-center">
            <div class="text-gray-600 dark:text-gray-400 text-sm">
                Mostrando <span class="font-semibold">{{ $ventas->firstItem() }}</span>
                al <span class="font-semibold">{{ $ventas->lastItem() }}</span>
                de <span class="font-semibold">{{ $ventas->total() }}</span> resultados.
            </div>
            <div>
                {{ $ventas->links() }}
            </div>
        </div>
    @endif
</x-layouts::app>
