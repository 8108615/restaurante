<x-layouts::app title="Editar Producto">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">
            <i class="fas fa-edit mr-2"></i> Editar producto / plato: {{ $producto->nombre }}
        </flux:heading>
        <br>
        <flux:separator variant="subtle" />
    </div>

    <div class="max-w-4xl bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg p-6 shadow-sm">
        {{-- ¡IMPORTANTE! enctype="multipart/form-data" es obligatorio para subir/actualizar imágenes --}}
        <form action="{{ route('admin.productos.update', $producto->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Seccion Principal: Grilla de Campos -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <!-- Nombre del Producto -->
                    <div>
                        <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre del Producto / Plato *</label>
                        <flux:input name="nombre" id="nombre" type="text" icon="tag" placeholder="Ej. Lomo Saltado, Hamburguesa doble..." value="{{ old('nombre', $producto->nombre) }}" required />
                        @error('nombre')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Categoría (Relación foreignId) -->
                    <div>
                        <label for="categoria_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Categoría *</label>
                        <select name="categoria_id" id="categoria_id" class="w-full rounded-lg border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500 text-sm p-2.5 shadow-sm" required>
                            <option value="">-- Seleccionar Categoría --</option>
                            @foreach ($categorias as $categoria)
                                <option value="{{ $categoria->id }}" {{ old('categoria_id', $producto->categoria_id) == $categoria->id ? 'selected' : '' }}>
                                    {{ $categoria->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('categoria_id')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Precio -->
                    <div>
                        <label for="precio" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Precio de Venta *</label>
                        <flux:input name="precio" id="precio" type="number" step="0.01" min="0" icon="currency-dollar" placeholder="0.00" value="{{ old('precio', $producto->precio) }}" required />
                        @error('precio')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Stock -->
                    <div>
                        <label for="stock" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Stock Actual *</label>
                        <flux:input name="stock" id="stock" type="number" min="0" icon="hashtag" placeholder="0" value="{{ old('stock', $producto->stock) }}" required />
                        @error('stock')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Estado -->
                    <div class="md:col-span-2">
                        <label for="estado" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Estado *</label>
                        <select name="estado" id="estado" class="w-full rounded-lg border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500 text-sm p-2.5 shadow-sm" required>
                            <option value="Activo" {{ old('estado', $producto->estado) == 'Activo' ? 'selected' : '' }}>Activo</option>
                            <option value="Inactivo" {{ old('estado', $producto->estado) == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                        @error('estado')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Descripción -->
                    <div class="md:col-span-2">
                        <label for="descripcion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descripción del Producto</label>
                        <textarea name="descripcion" id="descripcion" rows="3" class="w-full rounded-lg border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500 text-sm p-2.5 shadow-sm" placeholder="Ingredientes, detalles o características...">{{ old('descripcion', $producto->descripcion) }}</textarea>
                        @error('descripcion')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Cargar / Cambiar Imagen con Previsualización -->
                    <div class="md:col-span-2">
                        <label for="imagen" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Imagen / Foto del Producto</label>

                        <!-- Si ya tiene imagen, la mostramos actualmente -->
                        @if ($producto->imagen)
                            <div class="mb-3" id="current-image-container">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Imagen actual:</p>
                                <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}" class="h-32 w-32 object-cover rounded-lg border border-gray-300 dark:border-zinc-700 shadow-sm">
                            </div>
                        @endif

                        <div class="flex items-center gap-4">
                            <input type="file" name="imagen" id="imagen" accept="image/*" class="w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-zinc-700 dark:file:text-gray-300 cursor-pointer" onchange="previewImage(event)">
                        </div>

                        <!-- Vista previa de la nueva imagen cargada -->
                        <div class="mt-3 hidden" id="preview-container">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Vista previa de la nueva imagen:</p>
                            <img id="image-preview" src="#" alt="Previsualización de la nueva imagen" class="h-32 w-32 object-cover rounded-lg border border-gray-300 dark:border-zinc-700 shadow-sm">
                        </div>

                        @error('imagen')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                </div>

                <!-- Botones de Acción -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-zinc-700">
                    <a href="{{ route('admin.productos.index') }}"
                        class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition text-sm flex items-center gap-2">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition text-sm flex items-center gap-2 cursor-pointer">
                        <i class="fas/sync fa-save"></i> Actualizar Producto
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- JavaScript para Previsualización de Imagen -->
    <script>
        function previewImage(event) {
            const input = event.target;
            const previewContainer = document.getElementById('preview-container');
            const previewImage = document.getElementById('image-preview');
            const currentImageContainer = document.getElementById('current-image-container');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                    // Opcionalmente ocultamos la imagen actual mientras seleccionas una nueva
                    if (currentImageContainer) {
                        currentImageContainer.style.opacity = '0.4';
                    }
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                previewContainer.classList.add('hidden');
                if (currentImageContainer) {
                    currentImageContainer.style.opacity = '1';
                }
            }
        }
    </script>
</x-layouts::app>
