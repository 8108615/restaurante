<x-layouts::app title="Detalle de la Caja #{{ $caja->id }}">
    <div class="relative mb-6 w-full">
        <div class="flex justify-between items-center">
            <div>
                <flux:heading size="xl" level="1">
                    Detalle de la Caja #{{ $caja->id }}
                </flux:heading>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Revisa el resumen financiero y las ventas asociadas a este turno.
                </p>
            </div>
            <div class="flex gap-2">
                <button onclick="window.print()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition flex items-center gap-2 shadow-sm">
                    <i class="fas fa-print"></i> Imprimir Reporte
                </button>
                <a href="{{ route('admin.cajas.index') }}" class="px-4 py-2 bg-zinc-700 hover:bg-zinc-800 text-white font-semibold rounded-lg transition flex items-center gap-2 shadow-sm">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
        <br>
        <flux:separator variant="subtle" />
    </div>

    <!-- Tarjetas de Resumen Financiero -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <!-- Saldo Inicial -->
        <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-5 flex items-center justify-between shadow-sm">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-zinc-400 mb-1">Saldo Inicial</p>
                <h3 class="text-2xl font-black text-white">{{ $simboloDivisa }} {{ number_format($caja->saldo_inicial, 2) }}</h3>
            </div>
            <div class="p-3 bg-blue-600/20 text-blue-500 rounded-xl">
                <i class="fas fa-wallet fa-lg"></i>
            </div>
        </div>

        <!-- Total Vendido -->
        <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-5 flex items-center justify-between shadow-sm">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-zinc-400 mb-1">Total Vendido</p>
                <h3 class="text-2xl font-black text-emerald-400">{{ $simboloDivisa }} {{ number_format($totalVendido, 2) }}</h3>
            </div>
            <div class="p-3 bg-emerald-600/20 text-emerald-500 rounded-xl">
                <i class="fas fa-shopping-cart fa-lg"></i>
            </div>
        </div>

        <!-- Saldo Final / Actual -->
        <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-5 flex items-center justify-between shadow-sm">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-zinc-400 mb-1">Saldo Final / Actual</p>
                <h3 class="text-2xl font-black text-amber-400">
                    {{ $simboloDivisa }} {{ number_format($caja->saldo_final ?? ($caja->saldo_inicial + $totalVendido), 2) }}
                </h3>
            </div>
            <div class="p-3 bg-amber-600/20 text-amber-500 rounded-xl">
                <i class="fas fa-coins fa-lg"></i>
            </div>
        </div>

        <!-- Estado -->
        <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-5 flex items-center justify-between shadow-sm">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-zinc-400 mb-1">Estado</p>
                <div class="mt-1">
                    @if($caja->estado === 'abierto')
                        <span class="px-3 py-1 rounded-full text-xs bg-emerald-500/20 text-emerald-400 font-bold uppercase tracking-wide">Abierto</span>
                    @else
                        <span class="px-3 py-1 rounded-full text-xs bg-red-500/20 text-red-400 font-bold uppercase tracking-wide">Cerrado</span>
                    @endif
                </div>
            </div>
            <div class="p-3 bg-purple-600/20 text-purple-400 rounded-xl">
                <i class="fas fa-info-circle fa-lg"></i>
            </div>
        </div>
    </div>

    <!-- Información del Turno -->
    <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-6 mb-8 shadow-sm">
        <h4 class="text-sm font-bold uppercase tracking-wider text-zinc-400 mb-4">Información del Turno</h4>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
            <div class="flex items-center gap-3">
                <div class="text-zinc-500"><i class="fas fa-user fa-lg"></i></div>
                <div>
                    <span class="block text-xs text-zinc-500">Cajero:</span>
                    <span class="font-semibold text-white">{{ $caja->user->name ?? 'N/A' }}</span>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="text-zinc-500"><i class="fas fa-calendar-alt fa-lg"></i></div>
                <div>
                    <span class="block text-xs text-zinc-500">Fecha de Apertura:</span>
                    <span class="font-semibold text-white">{{ $caja->fecha_apertura }}</span>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="text-zinc-500"><i class="fas fa-calendar-check fa-lg"></i></div>
                <div>
                    <span class="block text-xs text-zinc-500">Fecha de Cierre:</span>
                    <span class="font-semibold text-white">{{ $caja->fecha_cierre ?? '---' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Ventas Realizadas en este Turno -->
    <div class="mb-4">
        <h4 class="text-lg font-bold text-white mb-2">Ventas Realizadas en este Turno</h4>
    </div>

    <div class="overflow-x-auto rounded-xl border border-zinc-800 bg-zinc-900 shadow-sm">
        <table class="min-w-full border-collapse">
            <thead class="bg-zinc-950 text-center">
                <tr>
                    <th class="px-4 py-3 border-b border-zinc-800 text-xs font-bold text-zinc-400 uppercase tracking-wider">NRO</th>
                    <th class="px-4 py-3 border-b border-zinc-800 text-xs font-bold text-zinc-400 uppercase tracking-wider">Comprobante</th>
                    <th class="px-4 py-3 border-b border-zinc-800 text-xs font-bold text-zinc-400 uppercase tracking-wider">Fecha</th>
                    <th class="px-4 py-3 border-b border-zinc-800 text-xs font-bold text-zinc-400 uppercase tracking-wider">Cliente</th>
                    <th class="px-4 py-3 border-b border-zinc-800 text-xs font-bold text-zinc-400 uppercase tracking-wider text-left">Productos</th>
                    <th class="px-4 py-3 border-b border-zinc-800 text-xs font-bold text-zinc-400 uppercase tracking-wider">Tipo Pago</th>
                    <th class="px-4 py-3 border-b border-zinc-800 text-xs font-bold text-zinc-400 uppercase tracking-wider">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-800">
                @forelse($caja->ventas as $index => $venta)
                    <tr class="hover:bg-zinc-800/50 transition">
                        <td class="px-4 py-3 text-sm text-center font-bold text-white">
                            {{ $index + 1 }}
                        </td>
                        <td class="px-4 py-3 text-sm text-center font-mono text-blue-400">
                            {{ $venta->comprobante ?? 'BO-' . str_pad($venta->id, 4, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="px-4 py-3 text-xs text-center text-zinc-400">
                            {{ $venta->created_at }}
                        </td>
                        <td class="px-4 py-3 text-sm text-center text-zinc-200">
                            {{ $venta->cliente->nombre ?? $venta->cliente_nombre ?? 'Público General' }}
                        </td>
                        <td class="px-4 py-3 text-xs text-zinc-300 text-left">
                            <ul class="space-y-1">
                                @foreach($venta->detalles as $detalle)
                                    <li>• {{ $detalle->producto->nombre ?? 'Producto' }} <span>(Cant: {{ $detalle->cantidad ?? 1 }})</span></li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="px-4 py-3 text-sm text-center text-zinc-300">
                            {{ $venta->tipo_pago ?? 'Efectivo' }}
                        </td>
                        <td class="px-4 py-3 text-sm text-center font-bold text-emerald-400">
                            {{ $simboloDivisa }} {{ number_format($venta->total, 2) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-zinc-500 text-sm">
                            <i class="fas fa-receipt fa-2x mb-2 block text-zinc-600"></i>
                            No se registraron ventas en este turno.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts::app>
