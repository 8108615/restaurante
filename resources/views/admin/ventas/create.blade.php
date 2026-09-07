<x-layouts::app title="Punto de Venta - POS">
    <div class="relative mb-4 w-full flex justify-between items-center">
        <flux:heading size="xl" level="1">
            <i class="fas fa-cash-register mr-2"></i> Punto de Venta (POS) / Caja
        </flux:heading>

        <a href="{{ route('admin.ventas.index') }}"
            class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition text-sm flex items-center gap-2">
            <i class="fas fa-list"></i> Ver Historial de Ventas
        </a>
    </div>

    <flux:separator variant="subtle" class="mb-6" />

    <!-- Contenedor Principal en Grid (Catálogo e Izquierda / Carrito a Derecha) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- ================= COLUMNA IZQUIERDA: CATÁLOGO DE PRODUCTOS (7 u 8 cols) ================= -->
        <div class="lg:col-span-7 xl:col-span-8 space-y-4">

            <!-- Buscador y Filtros -->
            <div class="bg-white dark:bg-zinc-800 p-4 rounded-lg border border-gray-200 dark:border-zinc-700 shadow-sm space-y-3">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" id="buscar-producto" placeholder="Buscar producto por nombre..."
                        class="w-full pl-10 pr-4 py-2 rounded-lg border-gray-300 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-900 text-gray-900 dark:text-gray-100 text-sm focus:border-blue-500 focus:ring-blue-500"
                        onkeyup="filtrarProductos()">
                </div>

                <!-- Botones de Categorías -->
                <div class="flex gap-2 overflow-x-auto pb-1 scrollbar-thin">
                    <button onclick="filtrarCategoria('todos')" class="cat-btn px-3 py-1.5 bg-blue-600 text-white text-xs font-semibold rounded-lg shrink-0 transition">
                        Todos
                    </button>
                    @foreach($categorias as $cat)
                        <button onclick="filtrarCategoria('{{ $cat->id }}')" class="cat-btn px-3 py-1.5 bg-gray-200 dark:bg-zinc-700 text-gray-700 dark:text-gray-300 text-xs font-semibold rounded-lg shrink-0 transition hover:bg-blue-500 hover:text-white">
                            {{ $cat->nombre }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Grilla de Productos -->
            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 max-h-[650px] overflow-y-auto pr-1" id="productos-grid">
                @forelse($productos as $prod)
                    <div class="producto-card bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg p-3 shadow-sm flex flex-col justify-between cursor-pointer hover:border-blue-500 hover:shadow-md transition"
                         data-name="{{ strtolower($prod->nombre) }}"
                         data-categoria="{{ $prod->categoria_id }}"
                         onclick="agregarAlCarrito({{ $prod->id }}, '{{ addslashes($prod->nombre) }}', {{ $prod->precio }}, {{ $prod->stock }})">

                        <div>
                            <div class="h-28 w-full bg-gray-100 dark:bg-zinc-900 rounded-md mb-2 overflow-hidden flex items-center justify-center">
                                @if($prod->imagen)
                                    <img src="{{ asset('storage/' . $prod->imagen) }}" alt="{{ $prod->nombre }}" class="h-full w-full object-cover">
                                @else
                                    <i class="fas fa-utensils text-gray-400 text-2xl"></i>
                                @endif
                            </div>
                            <h4 class="text-sm font-bold text-gray-900 dark:text-gray-100 truncate">{{ $prod->nombre }}</h4>
                            <span class="text-[10px] text-gray-500 dark:text-gray-400 block">{{ $prod->categoria->nombre ?? 'General' }}</span>
                        </div>

                        <div class="mt-3 flex items-center justify-between">
                            <span class="text-sm font-extrabold text-emerald-600 dark:text-emerald-400">
                                {{ $simboloDivisa ?? 'Bs' }} {{ number_format($prod->precio, 2) }}
                            </span>
                            <span class="text-[11px] px-2 py-0.5 rounded bg-gray-100 dark:bg-zinc-700 text-gray-600 dark:text-gray-300">
                                Stock: {{ $prod->stock }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-10 text-center text-gray-500 dark:text-gray-400">
                        No hay productos disponibles o con stock en este momento.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- ================= COLUMNA DERECHA: CARRITO Y COBRO (5 o 4 cols) ================= -->
        <div class="lg:col-span-5 xl:col-span-4 bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg p-4 shadow-sm flex flex-col justify-between h-[750px]">

            <!-- Cabecera Carrito -->
            <div>
                <div class="flex justify-between items-center pb-3 border-b border-gray-200 dark:border-zinc-700">
                    <h3 class="font-bold text-gray-900 dark:text-gray-100 text-base">
                        <i class="fas fa-shopping-cart mr-1 text-blue-500"></i> Detalle de Venta
                    </h3>
                    <button onclick="limpiarCarrito()" class="text-xs text-red-500 hover:text-red-700 font-semibold cursor-pointer">
                        <i class="fas fa-trash mr-1"></i> Limpiar
                    </button>
                </div>

                <!-- Lista de Ítems en el Carrito -->
                <div class="divide-y divide-gray-200 dark:divide-zinc-700 overflow-y-auto max-h-[290px] my-2 pr-1" id="carrito-lista">
                    <div class="py-12 text-center text-gray-400 text-sm" id="carrito-vacio">
                        <i class="fas fa-shopping-basket fa-2x mb-2 block"></i>
                        El carrito está vacío.<br>Selecciona productos a la izquierda.
                    </div>
                </div>
            </div>

            <!-- Resumen y Cobro -->
            <div class="border-t border-gray-200 dark:border-zinc-700 pt-3 space-y-3">

                <!-- Método de Pago -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Método de Pago *</label>
                    <select id="metodo_pago" onchange="cambiarMetodoPago()" class="w-full rounded-lg border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-gray-900 dark:text-gray-100 text-xs p-2">
                        <option value="Efectivo">Efectivo</option>
                        <option value="QR">QR / Transferencia</option>
                        <option value="Tarjeta">Tarjeta de Crédito / Débito</option>
                    </select>
                </div>

                <!-- Contenedor Dinámico según el Método de Pago -->
                <div id="seccion-metodo-pago" class="space-y-2">
                    <!-- Se inyecta automáticamente con JavaScript -->
                </div>

                <!-- Totales -->
                <div class="bg-gray-50 dark:bg-zinc-900 p-3 rounded-lg space-y-1">
                    <div class="flex justify-between text-base font-bold text-gray-900 dark:text-gray-100 pt-1 border-t border-gray-200 dark:border-zinc-700">
                        <span>Total a Pagar:</span>
                        <span id="total-pagar" class="text-emerald-600 dark:text-emerald-400 text-lg">{{ $simboloDivisa ?? 'Bs' }} 0.00</span>
                    </div>
                </div>

                <!-- Botón Procesar Venta -->
                <button onclick="procesarVenta()" id="btn-cobrar" disabled
                    class="w-full py-3 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white font-bold rounded-lg transition text-sm flex items-center justify-center gap-2 cursor-pointer shadow-md">
                    <i class="fas fa-check-circle"></i> Cobrar y Finalizar Venta
                </button>
            </div>

        </div>

    </div>

    <!-- Modal para Mostrar Imagen QR -->
    <div id="modal-qr" class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-xs hidden">
        <div class="bg-white dark:bg-zinc-800 border border-zinc-700 rounded-xl p-5 max-w-sm w-full shadow-2xl text-center space-y-4 relative">
            <h3 class="font-bold text-gray-900 dark:text-gray-100 text-sm">Escanee el código QR para pagar</h3>

            <div class="bg-white p-2 rounded-lg inline-block border border-gray-200 shadow-inner">
                <img src="{{ asset('img/QR.jpg') }}" alt="Código QR de Pago" class="w-60 h-60 object-contain mx-auto rounded">
            </div>

            <p class="text-[11px] text-gray-500 dark:text-gray-400">Una vez realizado el pago, cierre esta ventana para completar la venta.</p>

            <button onclick="cerrarModalQr()" class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg text-xs transition cursor-pointer">
                Cerrar y Continuar
            </button>
        </div>
    </div>

    <!-- ================= MODAL DE TICKET / COMPROBANTE DE VENTA ================= -->
    <div id="modal-ticket" class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-xs hidden">
        <div class="bg-white dark:bg-zinc-800 border border-zinc-700 rounded-xl p-6 max-w-sm w-full shadow-2xl space-y-4 relative max-h-[90vh] overflow-y-auto">

            <!-- Cabecera del Comprobante (Datos Empresa) -->
            <div class="text-center space-y-1 pb-3 border-b border-gray-200 dark:border-zinc-700">
                <img id="ticket-logo" src="" alt="Logo Empresa" class="w-12 h-12 object-contain mx-auto mb-1 hidden">
                <h3 id="ticket-empresa-nombre" class="font-bold text-gray-900 dark:text-gray-100 text-sm"></h3>
                <p id="ticket-empresa-direccion" class="text-[11px] text-gray-500 dark:text-gray-400"></p>
                <p id="ticket-empresa-celular" class="text-[11px] text-gray-500 dark:text-gray-400"></p>
            </div>

            <!-- Datos de la Venta -->
            <div class="text-[11px] text-gray-600 dark:text-gray-300 space-y-0.5">
                <div class="flex justify-between">
                    <span><strong>N° Venta:</strong> <span id="ticket-id"></span></span>
                    <span id="ticket-fecha"></span>
                </div>
                <div class="flex justify-between">
                    <span><strong>Método Pago:</strong> <span id="ticket-metodo"></span></span>
                </div>
            </div>

            <!-- Listado de Productos del Ticket -->
            <div class="border-t border-b border-gray-200 dark:border-zinc-700 py-2">
                <table class="w-full text-left text-[11px]">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-zinc-700 text-gray-500">
                            <th class="py-1">Cant / Prod</th>
                            <th class="py-1 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody id="ticket-productos-lista" class="divide-y divide-gray-100 dark:divide-zinc-700/50">
                        <!-- Inyectado por JS -->
                    </tbody>
                </table>
            </div>

            <!-- Totales -->
            <div class="space-y-1 text-xs pt-1">
                <div class="flex justify-between font-bold text-gray-900 dark:text-gray-100 text-sm">
                    <span>TOTAL:</span>
                    <span id="ticket-total" class="text-emerald-600 dark:text-emerald-400"></span>
                </div>
                <div class="flex justify-between text-[11px] text-gray-500">
                    <span>Monto Pagado:</span>
                    <span id="ticket-pagado"></span>
                </div>
                <div class="flex justify-between text-[11px] text-gray-500">
                    <span>Cambio (Vuelto):</span>
                    <span id="ticket-cambio"></span>
                </div>
            </div>

            <p class="text-center text-[10px] text-gray-400 pt-2 border-t border-gray-100 dark:border-zinc-700">¡Gracias por su preferencia!</p>

            <!-- Botones de Acción del Modal -->
            <div class="flex gap-2 pt-2">
                <button onclick="imprimirTicket()" class="w-1/2 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg text-xs transition cursor-pointer flex items-center justify-center gap-1">
                    <i class="fas fa-print"></i> Imprimir
                </button>
                <button onclick="cerrarModalTicketYReiniciar()" class="w-1/2 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg text-xs transition cursor-pointer flex items-center justify-center gap-1">
                    <i class="fas fa-check"></i> Nueva Venta
                </button>
            </div>
        </div>
    </div>

    <!-- Scripts JavaScript del POS -->
    <script>
        let carrito = [];

        document.addEventListener("DOMContentLoaded", function() {
            cambiarMetodoPago();
        });

        // Cambiar campos dinámicos según el método de pago seleccionado
        function cambiarMetodoPago() {
            let metodo = document.getElementById('metodo_pago').value;
            let contenedor = document.getElementById('seccion-metodo-pago');

            if (metodo === 'Efectivo') {
                contenedor.innerHTML = `
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 mb-1">Efectivo Recibido *</label>
                            <input type="number" step="0.01" id="monto_pagado" onkeyup="calcularCambio()" placeholder="0.00"
                                class="w-full rounded-lg border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-gray-900 dark:text-gray-100 text-xs p-2 font-bold">
                        </div>
                        <div>
                            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 mb-1">Cambio (Vuelto)</label>
                            <input type="text" id="cambio_devuelto" readonly value="0.00"
                                class="w-full rounded-lg border-gray-300 dark:border-zinc-700 bg-gray-100 dark:bg-zinc-800 text-emerald-600 dark:text-emerald-400 text-xs p-2 font-extrabold">
                        </div>
                    </div>
                `;
                calcularCambio();
            } else if (metodo === 'QR') {
                contenedor.innerHTML = `
                    <div>
                        <button type="button" onclick="abrirModalQr()" class="w-full py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg text-xs transition flex items-center justify-center gap-2 cursor-pointer shadow-sm">
                            <i class="fas fa-qrcode"></i> Mostrar Código QR de Pago
                        </button>
                    </div>
                `;
                validarHabilitarCobro();
            } else if (metodo === 'Tarjeta') {
                contenedor.innerHTML = `
                    <div>
                        <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 mb-1">N° de Voucher / Código de Transacción *</label>
                        <input type="text" id="codigo_tarjeta" onkeyup="validarHabilitarCobro()" placeholder="Ej. VOUCHER-9842"
                            class="w-full rounded-lg border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-gray-900 dark:text-gray-100 text-xs p-2 uppercase">
                    </div>
                `;
                validarHabilitarCobro();
            }
        }

        // Calcular el cambio en efectivo
        function calcularCambio() {
            let totalGeneral = carrito.reduce((acc, item) => acc + (item.precio * item.cantidad), 0);
            let inputMonto = document.getElementById('monto_pagado');
            let inputCambio = document.getElementById('cambio_devuelto');

            if (!inputMonto) return;

            let montoPagado = parseFloat(inputMonto.value) || 0;
            let cambio = montoPagado - totalGeneral;

            if (montoPagado >= totalGeneral) {
                inputCambio.value = cambio.toFixed(2);
                inputCambio.classList.remove('text-red-500');
                inputCambio.classList.add('text-emerald-600', 'dark:text-emerald-400');
            } else {
                inputCambio.value = "Insuficiente";
                inputCambio.classList.remove('text-emerald-600', 'dark:text-emerald-400');
                inputCambio.classList.add('text-red-500');
            }

            validarHabilitarCobro();
        }

        // Validaciones generales para activar/desactivar botón de cobrar
        function validarHabilitarCobro() {
            let btnCobrar = document.getElementById('btn-cobrar');
            if (carrito.length === 0) {
                btnCobrar.disabled = true;
                return;
            }

            let metodo = document.getElementById('metodo_pago').value;
            let totalGeneral = carrito.reduce((acc, item) => acc + (item.precio * item.cantidad), 0);

            if (metodo === 'Efectivo') {
                let montoPagado = parseFloat(document.getElementById('monto_pagado')?.value) || 0;
                if (montoPagado >= totalGeneral && totalGeneral > 0) {
                    btnCobrar.disabled = false;
                } else {
                    btnCobrar.disabled = true;
                }
            } else if (metodo === 'QR') {
                btnCobrar.disabled = totalGeneral <= 0;
            } else if (metodo === 'Tarjeta') {
                let codigo = document.getElementById('codigo_tarjeta')?.value.trim();
                if (codigo && codigo.length > 0 && totalGeneral > 0) {
                    btnCobrar.disabled = false;
                } else {
                    btnCobrar.disabled = true;
                }
            }
        }

        // Abrir y cerrar modal QR
        function abrirModalQr() {
            document.getElementById('modal-qr').classList.remove('hidden');
        }

        function cerrarModalQr() {
            document.getElementById('modal-qr').classList.add('hidden');
        }

        // Agregar producto al carrito
        function agregarAlCarrito(id, nombre, precio, stockMax) {
            let index = carrito.findIndex(item => item.id === id);

            if (index !== -1) {
                if (carrito[index].cantidad < stockMax) {
                    carrito[index].cantidad++;
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Stock límite',
                        text: 'No hay más stock disponible para este producto.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    return;
                }
            } else {
                carrito.push({
                    id: id,
                    nombre: nombre,
                    precio: precio,
                    cantidad: 1,
                    stock: stockMax
                });
            }
            renderCarrito();
        }

        // Cambiar cantidad (+ o -)
        function cambiarCantidad(id, delta) {
            let index = carrito.findIndex(item => item.id === id);
            if (index !== -1) {
                let nuevaCantidad = carrito[index].cantidad + delta;
                if (nuevaCantidad > 0 && nuevaCantidad <= carrito[index].stock) {
                    carrito[index].cantidad = nuevaCantidad;
                } else if (nuevaCantidad <= 0) {
                    carrito.splice(index, 1);
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Stock límite',
                        text: 'Has alcanzado el stock máximo disponible.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            }
            renderCarrito();
        }

        // Eliminar producto del carrito
        function eliminarDelCarrito(id) {
            carrito = carrito.filter(item => item.id !== id);
            renderCarrito();
        }

        // Limpiar todo el carrito
        function limpiarCarrito() {
            carrito = [];
            renderCarrito();
        }

        // Renderizar el carrito en pantalla
        function renderCarrito() {
            let contenedor = document.getElementById('carrito-lista');
            let totalPagarSpan = document.getElementById('total-pagar');

            contenedor.innerHTML = '';

            if (carrito.length === 0) {
                contenedor.innerHTML = `
                    <div class="py-12 text-center text-gray-400 text-sm" id="carrito-vacio">
                        <i class="fas fa-shopping-basket fa-2x mb-2 block"></i>
                        El carrito está vacío.<br>Selecciona productos a la izquierda.
                    </div>`;
                totalPagarSpan.innerText = "{{ $simboloDivisa ?? 'Bs' }} 0.00";
                validarHabilitarCobro();
                return;
            }

            let totalGeneral = 0;

            carrito.forEach(item => {
                let subtotal = item.precio * item.cantidad;
                totalGeneral += subtotal;

                let div = document.createElement('div');
                div.className = "py-2.5 flex items-center justify-between text-xs";
                div.innerHTML = `
                    <div class="flex-1 pr-2">
                        <span class="font-bold text-gray-900 dark:text-gray-100 block">${item.nombre}</span>
                        <span class="text-gray-500 dark:text-gray-400">{{ $simboloDivisa ?? 'Bs' }} ${item.precio.toFixed(2)} c/u</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <button onclick="cambiarCantidad(${item.id}, -1)" class="w-6 h-6 bg-gray-200 dark:bg-zinc-700 text-gray-700 dark:text-gray-200 rounded font-bold hover:bg-gray-300">-</button>
                        <span class="w-6 text-center font-bold text-gray-900 dark:text-gray-100">${item.cantidad}</span>
                        <button onclick="cambiarCantidad(${item.id}, 1)" class="w-6 h-6 bg-gray-200 dark:bg-zinc-700 text-gray-700 dark:text-gray-200 rounded font-bold hover:bg-gray-300">+</button>
                    </div>
                    <div class="w-14 text-right font-extrabold text-gray-900 dark:text-gray-100">
                        ${subtotal.toFixed(2)}
                    </div>
                    <button onclick="eliminarDelCarrito(${item.id})" class="ml-1.5 text-red-400 hover:text-red-600 transition p-1 cursor-pointer" title="Quitar producto">
                        <i class="fas fa-trash-alt text-xs"></i>
                    </button>
                `;
                contenedor.appendChild(div);
            });

            totalPagarSpan.innerText = "{{ $simboloDivisa ?? 'Bs' }} " + totalGeneral.toFixed(2);

            // Actualizar cálculos de efectivo y estado del botón
            let metodo = document.getElementById('metodo_pago').value;
            if (metodo === 'Efectivo') {
                calcularCambio();
            } else {
                validarHabilitarCobro();
            }
        }

        // Filtrar productos por buscador de texto
        function filtrarProductos() {
            let filtro = document.getElementById('buscar-producto').value.toLowerCase();
            let tarjetas = document.querySelectorAll('.producto-card');

            tarjetas.forEach(card => {
                let nombre = card.getAttribute('data-name');
                if (nombre.includes(filtro)) {
                    card.style.display = "flex";
                } else {
                    card.style.display = "none";
                }
            });
        }

        // Filtrar productos por categoría
        function filtrarCategoria(categoriaId) {
            let tarjetas = document.querySelectorAll('.producto-card');

            tarjetas.forEach(card => {
                let cat = card.getAttribute('data-categoria');
                if (categoriaId === 'todos' || cat === categoriaId) {
                    card.style.display = "flex";
                } else {
                    card.style.display = "none";
                }
            });
        }

        // Procesar Venta vía Fetch AJAX
        function procesarVenta() {
            if (carrito.length === 0) return;

            let totalGeneral = carrito.reduce((acc, item) => acc + (item.precio * item.cantidad), 0);
            let metodoPago = document.getElementById('metodo_pago').value;
            let montoPagado = metodoPago === 'Efectivo' ? (parseFloat(document.getElementById('monto_pagado').value) || totalGeneral) : totalGeneral;
            let cambio = metodoPago === 'Efectivo' ? (montoPagado - totalGeneral) : 0;
            let codigoTarjeta = metodoPago === 'Tarjeta' ? document.getElementById('codigo_tarjeta').value : null;

            Swal.fire({
                title: '¿Confirmar venta?',
                text: "Se registrará la transacción y se descontará el stock.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Sí, cobrar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {

                    fetch("{{ route('admin.ventas.store') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            productos: carrito,
                            metodo_pago: metodoPago,
                            total: totalGeneral,
                            monto_pagado: montoPagado,
                            cambio: cambio,
                            codigo_tarjeta: codigoTarjeta
                        })
                    })
                    .then(response => response.json().then(data => ({status: response.status, body: data})))
                    .then(res => {
                        if (res.body.success) {
                            // Pintar datos en el modal de ticket
                            llenarYMostrarModalTicket(res.body.venta, res.body.empresa);
                        } else {
                            Swal.fire('Error', res.body.mensaje, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Error', 'Ocurrió un error inesperado al procesar la venta.', 'error');
                    });

                }
            });
        }

        // Función para rellenar el modal con la respuesta del servidor
        function llenarYMostrarModalTicket(venta, empresa) {
            document.getElementById('ticket-empresa-nombre').innerText = empresa.nombre;
            document.getElementById('ticket-empresa-direccion').innerText = empresa.direccion;
            document.getElementById('ticket-empresa-celular').innerText = 'Cel: ' + empresa.celular;

            let logoImg = document.getElementById('ticket-logo');
            if (empresa.logo) {
                logoImg.src = empresa.logo;
                logoImg.classList.remove('hidden');
            }

            document.getElementById('ticket-id').innerText = '#' + venta.id;
            document.getElementById('ticket-fecha').innerText = venta.fecha;
            document.getElementById('ticket-metodo').innerText = venta.metodo_pago;
            document.getElementById('ticket-total').innerText = 'Bs ' + parseFloat(venta.total).toFixed(2);
            document.getElementById('ticket-pagado').innerText = 'Bs ' + parseFloat(venta.monto_pagado).toFixed(2);
            document.getElementById('ticket-cambio').innerText = 'Bs ' + parseFloat(venta.cambio).toFixed(2);

            let tbody = document.getElementById('ticket-productos-lista');
            tbody.innerHTML = '';

            venta.detalles.forEach(det => {
                let tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="py-1">
                        <span class="font-bold block">${det.nombre}</span>
                        <span class="text-gray-400">${det.cantidad} x Bs ${parseFloat(det.precio).toFixed(2)}</span>
                    </td>
                    <td class="py-1 text-right font-bold">Bs ${parseFloat(det.subtotal).toFixed(2)}</td>
                `;
                tbody.appendChild(tr);
            });

            // Mostrar el modal
            document.getElementById('modal-ticket').classList.remove('hidden');
        }

        // Imprimir ticket usando ventana de impresión del navegador
        function imprimirTicket() {
            window.print();
        }

        // Cerrar modal y limpiar carrito para la siguiente venta
        function cerrarModalTicketYReiniciar() {
            document.getElementById('modal-ticket').classList.add('hidden');
            limpiarCarrito();
            location.reload(); // Recarga la página para refrescar stock fresco de la BD
        }
    </script>

    <style>
    @media print {
        /* Ocultar absolutamente toda la página por defecto */
        body * {
            visibility: hidden !important;
        }

        /* Mostrar únicamente el contenedor del modal de ticket y sus hijos */
        #modal-ticket, #modal-ticket * {
            visibility: visible !important;
        }

        /* Resetear la posición del modal para que ocupe toda la hoja de impresión limpia */
        #modal-ticket {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            height: auto !important;
            background: white !important;
            backdrop-filter: none !important;
            display: block !important;
            z-index: 999999 !important;
        }

        /* Ocultar los botones de acción ("Imprimir" y "Nueva Venta") al mandar a la impresora */
        #modal-ticket button,
        #modal-ticket .flex.gap-2.pt-2 {
            display: none !important;
        }

        /* Ajustar los colores a modo oscuro/claro estándar de impresión (negro sobre blanco) */
        #modal-ticket .bg-white,
        #modal-ticket.dark\:bg-zinc-800 {
            background-color: #ffffff !important;
            color: #000000 !important;
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
        }

        #modal-ticket * {
            color: #000000 !important;
        }
    }
    </style>
</x-layouts::app>
