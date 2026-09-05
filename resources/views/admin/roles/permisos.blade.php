<x-layouts::app title="Asignar Permisos">
    <div class="relative mb-6 w-full">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <flux:heading size="xl" level="1">Asignar Permisos al Rol: <span class="text-blue-500 dark:text-blue-400">{{ $rol->name }}</span></flux:heading>
                <p class="text-sm text-gray-400 mt-1">Selecciona los permisos que tendrá este rol por cada módulo del sistema.</p>
            </div>

            <div class="flex items-center gap-3">
                <button type="button" id="btn-seleccionar-todos-global" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg shadow transition gap-2">
                    Seleccionar todos
                </button>
                <a href="{{ route('admin.roles.index') }}" class="inline-flex items-center px-4 py-2 bg-zinc-700 hover:bg-zinc-600 text-white text-xs font-semibold rounded-lg shadow transition">
                    Volver
                </a>
            </div>
        </div>
        <br>
        <flux:separator variant="subtle" />
    </div>

    <form action="{{ route('admin.roles.guardar_permisos', $rol->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Cuadrícula de 3 columnas idéntica a tu imagen de referencia -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @foreach($permisos as $modulo => $listaPermisos)
                <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-5 shadow-sm flex flex-col justify-between">
                    <div>
                        <!-- Cabecera del módulo con icono de carpeta y botón seleccionar todos -->
                        <div class="flex justify-between items-center pb-3 mb-4 border-b border-zinc-800">
                            <h3 class="font-bold text-xs text-white uppercase tracking-wider flex items-center gap-2">
                                <i class="fas fa-folder text-blue-500"></i> {{ $modulo }}
                            </h3>
                            <button type="button" class="text-xs text-blue-400 hover:text-blue-300 font-semibold btn-seleccionar-modulo transition" data-modulo="{{ $loop->index }}">
                                Seleccionar todos
                            </button>
                        </div>

                        <!-- Lista de permisos en checkboxes -->
                        <div class="grid grid-cols-1 gap-2.5">
                            @foreach($listaPermisos as $permiso)
                                <label class="flex items-center space-x-3 p-2 hover:bg-zinc-800/50 rounded-lg cursor-pointer transition">
                                    <input type="checkbox" name="permisos[]" value="{{ $permiso->name }}"
                                        {{ $rol->hasPermissionTo($permiso->name) ? 'checked' : '' }}
                                        class="w-4 h-4 text-blue-600 bg-zinc-800 border-zinc-700 rounded focus:ring-blue-500 dark:ring-offset-zinc-900 focus:ring-2 checkbox-permiso checkbox-modulo-{{ $loop->parent->index }}">
                                    <span class="text-xs font-medium text-zinc-300">{{ $permiso->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Botones de Acción Finales (Guardar y Cancelar) -->
        <div class="sticky bottom-4 z-10 mt-8">
            <div class="flex items-center justify-end gap-3 bg-zinc-900/90 backdrop-blur-md p-4 border border-zinc-800 rounded-xl shadow-2xl">
                <a href="{{ route('admin.roles.index') }}" class="px-5 py-2.5 bg-zinc-700 hover:bg-zinc-600 text-white text-xs font-semibold rounded-lg transition">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg shadow transition">
                    Guardar Permisos
                </button>
            </div>
        </div>
    </form>

    <!-- Script de selección múltiple -->
    <script>
        document.getElementById('btn-seleccionar-todos-global').addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('.checkbox-permiso');
            const todosMarcados = Array.from(checkboxes).every(cb => cb.checked);

            checkboxes.forEach(cb => {
                cb.checked = !todosMarcados;
            });

            this.textContent = todosMarcados ? 'Seleccionar todos' : 'Deseleccionar todos';
        });

        document.querySelectorAll('.btn-seleccionar-modulo').forEach(button => {
            button.addEventListener('click', function() {
                const moduloIndex = this.getAttribute('data-modulo');
                const checkboxesModulo = document.querySelectorAll('.checkbox-modulo-' + moduloIndex);
                const todosMarcadosModulo = Array.from(checkboxesModulo).every(cb => cb.checked);

                checkboxesModulo.forEach(cb => {
                    cb.checked = !todosMarcadosModulo;
                });

                this.textContent = todosMarcadosModulo ? 'Seleccionar todos' : 'Deseleccionar todos';
            });
        });
    </script>
</x-layouts::app>
