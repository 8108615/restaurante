<x-layouts::app title="Crear Categoría">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Registrar nueva categoría</flux:heading>
        <br>
        <flux:separator variant="subtle" />
    </div>

    <div class="max-w-2xl bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg p-6 shadow-sm">
        <form action="{{ route('admin.categorias.store') }}" method="POST">
            @csrf

            <div class="space-y-4">
                <!-- Nombre -->
                <div>
                    <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre de la Categoría *</label>
                    <flux:input name="nombre" id="nombre" type="text" icon="tag" placeholder="Ej. Lácteos, Bebidas, Abarrotes..." value="{{ old('nombre') }}" required />
                    @error('nombre')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Descripción -->
                <div>
                    <label for="descripcion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descripción</label>
                    <textarea name="descripcion" id="descripcion" rows="3" class="w-full rounded-lg border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500 text-sm p-2.5 shadow-sm" placeholder="Breve descripción de la categoría...">{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Estado -->
                <div>
                    <label for="estado" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Estado *</label>
                    <select name="estado" id="estado" class="w-full rounded-lg border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500 text-sm p-2.5 shadow-sm" required>
                        <option value="Activo" {{ old('estado') == 'Activo' ? 'selected' : '' }}>Activo</option>
                        <option value="Inactivo" {{ old('estado') == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                    @error('estado')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Botones de Acción -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-zinc-700">
                    <a href="{{ route('admin.categorias.index') }}"
                        class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition text-sm">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition text-sm flex items-center gap-2 cursor-pointer">
                        <i class="fas fa-save"></i> Guardar Categoría
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-layouts::app>