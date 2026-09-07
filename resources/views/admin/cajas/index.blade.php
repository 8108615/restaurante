<x-layouts::app title="Gestión de Cajas">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">
            Gestión de Cajas
        </flux:heading>
        <br>
        <flux:separator variant="subtle" />
    </div>

    <!-- Barra de Búsqueda y Botón Abrir Caja -->
    <div class="flex gap-4 items-center justify-between mb-6">
        <div class="flex-1">
            <form action="{{ route('admin.cajas.index') }}" method="GET" class="flex gap-2 w-1/2">
                <div class="flex-1">
                    <flux:input name="buscar" type="text" icon="magnifying-glass" placeholder="Buscar por estado o cajero..."
                        value="{{ request('buscar') }}" class="transition-all duration-200" />
                </div>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition flex items-center gap-2">
                    <i class="fas fa-search"></i> Buscar
                </button>
                @if (request('buscar'))
                    <a href="{{ route('admin.cajas.index') }}"
                        class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                        <i class="fas fa-trash"></i> Limpiar
                    </a>
                @endif
            </form>
        </div>

        <div>
            @if(!$cajaAbierta)
                <flux:modal.trigger name="modal-abrir-caja">
                    <button type="button"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition flex items-center gap-2 shadow-sm cursor-pointer">
                        <i class="fas fa-plus"></i> Abrir Caja
                    </button>
                </flux:modal.trigger>
            @else
                <button type="button" onclick="confirmarCierre({{ $cajaAbierta->id }})"
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition flex items-center gap-2 shadow-sm cursor-pointer">
                    <i class="fas fa-lock"></i> Cerrar Caja Actual (#{{ $cajaAbierta->id }})
                </button>
                <form id="formCerrar{{ $cajaAbierta->id }}" action="{{ route('admin.cajas.cerrar', $cajaAbierta->id) }}" method="POST" class="hidden">
                    @csrf
                </form>
            @endif
        </div>
    </div>

    <!-- Mensajes de Alerta -->
    @if (session('success'))
        <div class="mb-4 p-4 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 rounded-lg text-sm">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 rounded-lg text-sm">
            <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
        </div>
    @endif

    <!-- Alerta de resultados de búsqueda -->
    @if (request('buscar'))
        <div class="mb-4 p-3 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg text-sm">
            <p class="text-gray-700 dark:text-gray-300">
                <i class="fas fa-search mr-2"></i>
                Se {{ $cajasHistorial->total() == 1 ? 'encontró' : 'encontraron' }}
                <span class="font-semibold text-blue-600 dark:text-blue-400">{{ $cajasHistorial->total() }}</span>
                {{ $cajasHistorial->total() == 1 ? 'resultado' : 'resultados' }}
                para: <span class="font-semibold">"{{ request('buscar') }}"</span>
            </p>
        </div>
    @endif

    <!-- Tabla Principal Estilo Compacto -->
    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 shadow-sm">
        <table class="min-w-full border-collapse">
            <thead class="bg-gray-50 dark:bg-zinc-900 text-center">
                <tr>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">NRO</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Cajero</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Saldo Inicial</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Saldo Final</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fecha Apertura</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fecha Cierre</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-zinc-800">
                @forelse($cajasHistorial as $caja)
                    <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition">
                        <td class="px-3.5 py-2.5 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 font-bold text-center">
                            {{ $caja->id }}
                        </td>
                        <td class="px-3.5 py-2.5 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 text-center">
                            {{ $caja->user->name ?? 'N/A' }}
                        </td>
                        <td class="px-3.5 py-2.5 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-sm font-semibold text-gray-800 dark:text-gray-200 text-center">
                            Bs {{ number_format($caja->saldo_inicial, 2) }}
                        </td>
                        <td class="px-3.5 py-2.5 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-sm font-bold text-emerald-600 dark:text-emerald-400 text-center">
                            {{ $caja->saldo_final ? 'Bs ' . number_format($caja->saldo_final, 2) : 'En proceso' }}
                        </td>
                        <td class="px-3.5 py-2.5 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-xs text-gray-500 dark:text-gray-400 text-center">
                            {{ $caja->fecha_apertura }}
                        </td>
                        <td class="px-3.5 py-2.5 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-xs text-gray-500 dark:text-gray-400 text-center">
                            {{ $caja->fecha_cierre ?? '---' }}
                        </td>
                        <td class="px-3.5 py-2.5 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-center">
                            @if($caja->estado === 'abierto')
                                <span class="px-2.5 py-1 rounded-full text-xs bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400 font-semibold">Abierto</span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-xs bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 font-semibold">Cerrado</span>
                            @endif
                        </td>
                        <td class="px-3.5 py-2.5 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-center">
                            <div class="flex justify-center gap-2">
                                <!-- Botón Ver -->
                                <a href="#" onclick="verDetalleCaja({{ $caja->id }}, '{{ $caja->user->name ?? 'N/A' }}', '{{ $caja->saldo_inicial }}', '{{ $caja->total_ventas }}', '{{ $caja->saldo_final }}', '{{ $caja->fecha_apertura }}', '{{ $caja->fecha_cierre }}', '{{ $caja->estado }}')"
                                    class="inline-flex items-center px-2.5 py-1 bg-gray-500 hover:bg-gray-600 text-white text-xs font-semibold rounded transition" title="Ver Detalles">
                                    <i class="fas fa-eye mr-1"></i> Ver
                                </a>

                                <!-- Botón Ticket / Imprimir Arqueo -->
                                <a href="#" onclick="window.print()"
                                    class="inline-flex items-center px-2.5 py-1 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-semibold rounded transition" title="Imprimir Reporte">
                                    <i class="fas fa-print mr-1"></i>
                                </a>

                                <!-- Botón Eliminar -->
                                <form action="{{ route('admin.cajas.destroy', $caja->id) }}" method="POST" id="formEliminar{{ $caja->id }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center px-2.5 py-1 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded transition cursor-pointer"
                                        onclick="confirmarEliminacion(event, {{ $caja->id }})" title="Eliminar Registro">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400 text-sm">
                            <i class="fas fa-folder-open fa-2x mb-2 block text-gray-400"></i>
                            No hay registros de cajas disponibles.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    @if ($cajasHistorial->hasPages())
        <div class="px-3 mt-4 flex justify-between items-center text-sm">
            <div class="text-gray-600 dark:text-gray-400">
                Mostrando <span class="font-semibold">{{ $cajasHistorial->firstItem() }}</span>
                al <span class="font-semibold">{{ $cajasHistorial->lastItem() }}</span>
                de <span class="font-semibold">{{ $cajasHistorial->total() }}</span> resultados.
            </div>
            <div>
                {{ $cajasHistorial->links() }}
            </div>
        </div>
    @endif

    <!-- Modal Nativo de Flux para Abrir Caja -->
    <flux:modal name="modal-abrir-caja" class="md:w-96">
        <form action="{{ route('admin.cajas.store') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <flux:heading size="lg">Abrir Nueva Caja</flux:heading>
                <flux:subheading>Ingresa el monto inicial con el que abrirás la caja.</flux:subheading>
            </div>

            <flux:input
                type="number"
                step="0.01"
                name="saldo_inicial"
                label="Monto Inicial (Bs)"
                placeholder="0.00"
                icon="credit-card"
                required
                autofocus
            />

            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="subtle">Cancelar</flux:button>
                </flux:modal.close>

                <flux:button type="submit" variant="primary">Guardar</flux:button>
            </div>
        </form>
    </flux:modal>

    <!-- Scripts de SweetAlert2 -->
    <script>
        function confirmarCierre(id) {
            Swal.fire({
                title: '¿Desea cerrar la caja actual?',
                text: 'Se calcularán las ventas totales del turno y se cerrará la caja.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, cerrar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#dc2626'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('formCerrar' + id).submit();
                }
            });
        }

        function confirmarEliminacion(event, id) {
            event.preventDefault();
            Swal.fire({
                title: '¿Desea eliminar este registro?',
                text: 'Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Eliminar',
                confirmButtonColor: '#dc2626',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('formEliminar' + id).submit();
                }
            });
        }

        function verDetalleCaja(id, cajero, inicial, ventas, final, apertura, cierre, estado) {
            Swal.fire({
                title: `Detalle de Caja #${id}`,
                html: `
                    <div class="text-left space-y-2 text-sm">
                        <p><strong>Cajero:</strong> ${cajero}</p>
                        <p><strong>Estado:</strong> ${estado.toUpperCase()}</p>
                        <p><strong>Saldo Inicial:</strong> Bs ${Number(inicial).toFixed(2)}</p>
                        <p><strong>Total Ventas:</strong> Bs ${Number(ventas).toFixed(2)}</p>
                        <p><strong>Saldo Final:</strong> ${final !== 'null' && final !== '' ? 'Bs ' + Number(final).toFixed(2) : 'En proceso'}</p>
                        <hr class="my-2">
                        <p class="text-xs text-gray-500"><strong>Apertura:</strong> ${apertura}</p>
                        <p class="text-xs text-gray-500"><strong>Cierre:</strong> ${cierre !== 'null' ? cierre : '---'}</p>
                    </div>
                `,
                confirmButtonText: 'Cerrar',
                confirmButtonColor: '#2563eb'
            });
        }
    </script>
</x-layouts::app>
