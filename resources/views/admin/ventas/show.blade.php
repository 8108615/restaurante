<x-layouts::app title="Detalle de Venta #{{ $venta->id }}">

    <!-- Encabezado y Botones de Acción -->
    <div class="relative mb-6 w-full flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <flux:heading size="xl" level="1">
            <i class="fas fa-file-invoice-dollar mr-2 text-blue-600"></i> Detalle de Venta <span class="text-gray-400">#{{ $venta->id }}</span>
        </flux:heading>

        <div class="flex items-center gap-2">
            <!-- Botón Volver -->
            <a href="{{ route('admin.ventas.index') }}"
                class="px-4 py-2 bg-gray-200 dark:bg-zinc-700 hover:bg-gray-300 dark:hover:bg-zinc-600 text-gray-700 dark:text-gray-200 font-semibold rounded-lg transition text-sm flex items-center gap-2 shadow-sm">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <!-- Botón Imprimir Ticket -->
            <button onclick="window.print();"
                class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition text-sm flex items-center gap-2 shadow-sm cursor-pointer">
                <i class="fas fa-print"></i> Imprimir Ticket
            </button>
        </div>
    </div>

    <flux:separator variant="subtle" class="mb-6" />

    <!-- Contenedor Principal del Comprobante -->
    <div class="max-w-4xl mx-auto bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg shadow-sm p-6 sm:p-8 space-y-6 print-container">

        <!-- Cabecera / Datos de la Empresa y Estado -->
        <div class="flex flex-col sm:flex-row justify-between items-center border-b border-gray-200 dark:border-zinc-700 pb-6 gap-4">
            <div class="text-center sm:text-left">
                @if(isset($ajuste) && $ajuste->logo)
                    <img src="{{ asset('storage/' . $ajuste->logo) }}" alt="Logo" class="h-10 mx-auto sm:mx-0 mb-1 object-contain" style="max-height: 40px;">
                @else
                    <h2 class="text-xl font-black text-blue-600 dark:text-blue-400">{{ $ajuste->nombre ?? 'ERICK SYSTEMS' }}</h2>
                @endif
                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $ajuste->descripcion ?? 'Sistema de Gestión y Ventas' }}</p>
            </div>

            <div class="text-center">
                <span class="text-xs font-bold tracking-wider text-gray-400 uppercase block mb-1">Comprobante</span>
                <span class="px-3 py-1 rounded-full text-xs font-semibold
                    {{ $venta->estado === 'Completado' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' }}">
                    {{ strtoupper($venta->estado) }}
                </span>
            </div>

            <div class="text-center sm:text-right text-sm space-y-1">
                <p class="font-bold text-gray-800 dark:text-gray-200">Nº Venta: #{{ str_pad($venta->id, 6, '0', STR_PAD_LEFT) }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400"><i class="far fa-calendar-alt mr-1"></i> {{ $venta->created_at->format('d/m/Y H:i:s') }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400"><i class="fas fa-user mr-1"></i> Cajero: {{ $venta->user->name ?? 'N/A' }}</p>
            </div>
        </div>

        <!-- Información de Negocio y Pago -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50 dark:bg-zinc-900/50 p-4 rounded-lg text-sm border border-gray-100 dark:border-zinc-700/50">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase mb-1">Datos del Negocio:</p>
                <p class="text-gray-700 dark:text-gray-300"><strong>Dirección:</strong> {{ $ajuste->direccion ?? 'Santa Cruz, Bolivia' }}</p>
                <p class="text-gray-700 dark:text-gray-300"><strong>Teléfono:</strong> {{ $ajuste->telefono ?? 'No registrado' }}</p>
            </div>
            <div class="md:text-right">
                <p class="text-xs font-bold text-gray-400 uppercase mb-1">Detalles de Pago:</p>
                <p class="text-gray-700 dark:text-gray-300"><strong>Método:</strong> <span class="text-blue-600 dark:text-blue-400 font-semibold">{{ $venta->metodo_pago }}</span></p>
                @if($venta->metodo_pago == 'Efectivo')
                    <p class="text-gray-700 dark:text-gray-300"><strong>Pagado:</strong> {{ number_format($venta->monto_pagado, 2) }} {{ $ajuste->divisa ?? 'Bs' }}</p>
                    <p class="text-gray-700 dark:text-gray-300"><strong>Cambio:</strong> {{ number_format($venta->cambio, 2) }} {{ $ajuste->divisa ?? 'Bs' }}</p>
                @endif
            </div>
        </div>

        <!-- Tabla de Productos -->
        <div class="overflow-x-auto border border-gray-200 dark:border-zinc-700 rounded-lg">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-zinc-700 text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-zinc-900">
                        <th class="p-3 text-center w-12">#</th>
                        <th class="p-3">Producto</th>
                        <th class="p-3 text-center">Cant.</th>
                        <th class="p-3 text-right">Precio Unit.</th>
                        <th class="p-3 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-zinc-700 text-gray-800 dark:text-gray-200">
                    @foreach($venta->detalles as $index => $detalle)
                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-900/50 transition">
                            <td class="p-3 text-center text-gray-500">{{ $index + 1 }}</td>
                            <td class="p-3 font-medium">{{ $detalle->producto->nombre ?? 'Producto Eliminado' }}</td>
                            <td class="p-3 text-center">{{ $detalle->cantidad }}</td>
                            <td class="p-3 text-right">{{ number_format($detalle->precio_unitario, 2) }}</td>
                            <td class="p-3 text-right font-bold text-gray-900 dark:text-white">{{ number_format($detalle->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totales -->
        <div class="flex justify-end pt-2">
            <div class="w-full sm:w-72 bg-gray-50 dark:bg-zinc-900/50 p-4 rounded-lg border border-gray-200 dark:border-zinc-700 space-y-2">
                <div class="flex justify-between items-center text-base font-extrabold text-gray-900 dark:text-white">
                    <span>Total a Pagar:</span>
                    <span class="text-emerald-600 dark:text-emerald-400 text-lg">
                        {{ number_format($venta->total, 2) }} {{ $ajuste->divisa ?? 'Bs' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Pie de página -->
        <div class="text-center pt-6 border-t border-gray-200 dark:border-zinc-700 text-gray-500 dark:text-gray-400 text-xs space-y-1">
            <p class="font-medium">¡Gracias por su preferencia!</p>
            <p>{{ $ajuste->web ?? '' }}</p>
        </div>

    </div>

    <!-- Estilos de Impresión Limpios para Tailwind -->
    @push('styles')
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            .print-container, .print-container * {
                visibility: visible;
            }
            .print-container {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                border: none !important;
                box-shadow: none !important;
                background: white !important;
            }
            header, nav, aside, footer, button, a {
                display: none !important;
            }
        }
    </style>
    @endpush
    @push('scripts')
    <script>
        // Si la URL contiene ?print=true, disparamos la impresión de forma automática al cargar la página
        window.addEventListener('DOMContentLoaded', (event) => {
            const urlParams = new URLSearchParams(window.search || window.location.search);
            if (urlParams.get('print') === 'true') {
                setTimeout(() => {
                    window.print();
                }, 500); // Pequeño retraso para asegurar que cargue el logo y los estilos
            }
        });
    </script>
    @endpush

</x-layouts::app>
