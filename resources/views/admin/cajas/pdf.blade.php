<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Turno - Caja #{{ $caja->id }}</title>
    <!-- Tailwind CSS (CDN para asegurar que los estilos oscuros y de impresión carguen bien) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @media print {
            body {
                background-color: #09090b !important; /* bg-zinc-950 */
                color: #f4f4f5 !important; /* text-zinc-100 */
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .print\:hidden {
                display: none !important;
            }
        }
    </style>
</head>
<body class="bg-zinc-950 text-zinc-100 font-sans p-6 sm:p-10">

    <div class="max-w-5xl mx-auto">

        <!-- Botón Superior de Imprimir (Se oculta al imprimir) -->
        <div class="text-center mb-8 print:hidden">
            <button onclick="window.print()" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg transition flex items-center gap-2 mx-auto">
                <i class="fas fa-print"></i> Imprimir / Guardar PDF
            </button>
        </div>

        <!-- Contenedor Estilo Reporte Dark -->
        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-8 shadow-2xl">

            <!-- Cabecera -->
            <div class="text-center mb-8 pb-4 border-b border-zinc-800">
                <h1 class="text-xl font-black uppercase tracking-wider text-white">REPORTE DE TURNO / CAJA</h1>
                <p class="text-xs text-zinc-400 mt-1">Minimarket - Resumen de Operaciones</p>
            </div>

            <!-- Datos Generales del Turno -->
            <div class="grid grid-cols-1 md:grid-cols-2 border border-zinc-800 rounded-xl overflow-hidden mb-8 text-sm bg-zinc-950/60">
                <div class="divide-y divide-zinc-800">
                    <div class="flex px-4 py-3">
                        <span class="w-36 font-semibold text-zinc-400">ID de Caja:</span>
                        <span class="text-white font-bold">#{{ $caja->id }}</span>
                    </div>
                    <div class="flex px-4 py-3">
                        <span class="w-36 font-semibold text-zinc-400">Cajero:</span>
                        <span class="text-white">{{ $caja->user->name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex px-4 py-3">
                        <span class="w-36 font-semibold text-zinc-400">Saldo Inicial:</span>
                        <span class="text-white font-mono">{{ $simboloDivisa }} {{ number_format($caja->saldo_inicial, 2) }}</span>
                    </div>
                </div>
                <div class="divide-y divide-zinc-800 border-t md:border-t-0 md:border-l border-zinc-800">
                    <div class="flex px-4 py-3">
                        <span class="w-36 font-semibold text-zinc-400">Estado:</span>
                        <span>
                            <span class="px-2.5 py-0.5 rounded-full text-xs bg-red-500/20 text-red-400 font-bold uppercase">Cerrado</span>
                        </span>
                    </div>
                    <div class="flex px-4 py-3">
                        <span class="w-36 font-semibold text-zinc-400">Fecha Apertura:</span>
                        <span class="text-white font-mono text-xs">{{ $caja->fecha_apertura }}</span>
                    </div>
                    <div class="flex px-4 py-3">
                        <span class="w-36 font-semibold text-zinc-400">Fecha Cierre:</span>
                        <span class="text-white font-mono text-xs">{{ $caja->fecha_cierre ?? '---' }}</span>
                    </div>
                </div>
            </div>

            <!-- Título Tabla Detalle -->
            <h3 class="text-sm font-bold uppercase tracking-wider text-zinc-400 mb-3">Detalle de Ventas del Turno</h3>

            <!-- Tabla de Ventas -->
            <div class="overflow-x-auto rounded-xl border border-zinc-800 bg-zinc-950 mb-8">
                <table class="min-w-full border-collapse text-left">
                    <thead class="bg-zinc-900 text-zinc-400 text-xs uppercase tracking-wider border-b border-zinc-800">
                        <tr>
                            <th class="px-4 py-3 text-center">Nro</th>
                            <th class="px-4 py-3 text-center">Comprobante</th>
                            <th class="px-4 py-3 text-center">Fecha</th>
                            <th class="px-4 py-3 text-center">Cliente</th>
                            <th class="px-4 py-3 text-left">Productos</th>
                            <th class="px-4 py-3 text-center">Método Pago</th>
                            <th class="px-4 py-3 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-800 text-sm">
                        @forelse($caja->ventas as $index => $venta)
                            <tr class="hover:bg-zinc-900/50">
                                <td class="px-4 py-3 text-center font-bold text-white">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 text-center font-mono text-blue-400">BO-{{ str_pad($venta->id, 4, '0', STR_PAD_LEFT) }}</td>
                                <td class="px-4 py-3 text-center text-xs text-zinc-400 font-mono">{{ $venta->created_at }}</td>
                                <td class="px-4 py-3 text-center text-zinc-200">{{ $venta->cliente->nombre ?? 'Público General' }}</td>
                                <td class="px-4 py-3 text-xs text-zinc-300">
                                    <ul class="space-y-1">
                                        @foreach($venta->detalles as $detalle)
                                            <li>• {{ $detalle->producto->nombre ?? 'Producto' }} <span class="text-zinc-500">(Cant: {{ $detalle->cantidad }})</span></li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="px-4 py-3 text-center text-zinc-300">{{ $venta->metodo_pago }}</td>
                                <td class="px-4 py-3 text-right font-bold font-mono text-emerald-400">{{ $simboloDivisa }} {{ number_format($venta->total, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-zinc-500 text-sm">
                                    No se registraron ventas en este turno.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Cuadro de Totales (Alineado a la derecha) -->
            <div class="flex justify-end mb-16">
                <div class="w-80 border border-zinc-800 rounded-xl overflow-hidden bg-zinc-950 text-sm">
                    <div class="flex justify-between px-4 py-2.5 border-b border-zinc-800">
                        <span class="text-zinc-400 font-medium">Saldo Inicial:</span>
                        <span class="text-white font-mono">{{ $simboloDivisa }} {{ number_format($caja->saldo_inicial, 2) }}</span>
                    </div>
                    <div class="flex justify-between px-4 py-2.5 border-b border-zinc-800">
                        <span class="text-zinc-400 font-medium">Total Vendido:</span>
                        <span class="text-emerald-400 font-mono font-bold">{{ $simboloDivisa }} {{ number_format($totalVendido, 2) }}</span>
                    </div>
                    <div class="flex justify-between px-4 py-3 bg-zinc-900 text-base font-bold">
                        <span class="text-white uppercase tracking-wider">SALDO FINAL:</span>
                        <span class="text-amber-400 font-mono">{{ $simboloDivisa }} {{ number_format($caja->saldo_final ?? ($caja->saldo_inicial + $totalVendido), 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Líneas de Firmas al Pie -->
            <div class="grid grid-cols-2 gap-16 pt-8 text-center">
                <div>
                    <div class="border-t border-zinc-700 w-3/4 mx-auto mb-2"></div>
                    <p class="font-bold text-white text-sm">{{ $caja->user->name ?? 'N/A' }}</p>
                    <p class="text-xs text-zinc-400 uppercase tracking-wider">CAJERO / RESPONSABLE</p>
                </div>
                <div>
                    <div class="border-t border-zinc-700 w-3/4 mx-auto mb-2"></div>
                    <p class="font-bold text-white text-sm">SUPERVISOR / ADMINISTRACIÓN</p>
                    <p class="text-xs text-zinc-400 uppercase tracking-wider">VB GERENCIA</p>
                </div>
            </div>

        </div>
    </div>

</body>
</html>
