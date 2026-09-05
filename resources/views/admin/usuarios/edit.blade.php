<x-layouts::app title="Editar Usuario">
    <div class="mb-6 w-full">
        <flux:heading size="xl">Editar Usuario: {{ $usuario->name }}</flux:heading>
        <flux:separator variant="subtle" class="my-4" />
    </div>

    <form action="{{ route('admin.usuarios.update', $usuario->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="bg-white dark:bg-neutral-800 p-6 rounded-lg border border-gray-200 dark:border-gray-700">

            {{-- Foto de Perfil --}}
            <div class="col-span-1 md:col-span-4 mb-8">
                <flux:label>Foto de Perfil</flux:label>
                <div class="flex items-center gap-6 mt-2">
                    <div class="h-20 w-20 flex-shrink-0 relative">
                        <div class="h-full w-full rounded-2xl border-2 border-slate-100 overflow-hidden bg-slate-50 flex items-center justify-center shadow-inner">
                            {{-- Si existe foto, se muestra. Si no, se oculta --}}
                            <img id="image-preview"
                                 src="{{ $usuario->foto_perfil ? asset('storage/' . $usuario->foto_perfil) : '#' }}"
                                 alt="Preview"
                                 class="{{ $usuario->foto_perfil ? '' : 'hidden' }} h-full w-full object-cover">

                            <flux:icon id="placeholder-icon" name="photo" class="{{ $usuario->foto_perfil ? 'hidden' : '' }} text-slate-300 h-8 w-8" />
                        </div>
                    </div>
                    <div class="flex flex-col gap-2">
                        <input type="file" name="foto_perfil" id="foto-input" class="hidden" accept="image/*">
                        <label for="foto-input" class="cursor-pointer flex items-center gap-2 px-6 py-3 bg-white border-2 border-slate-200 rounded-2xl text-slate-600 font-bold hover:border-indigo-500 transition-all">
                            <flux:icon name="cloud-arrow-up" variant="micro" />
                            <span>Cambiar Foto</span>
                        </label>
                        <span id="file-chosen" class="text-sm text-slate-400 italic">
                            {{ $usuario->foto_perfil ? 'Foto actual cargada' : 'Ningún archivo seleccionado' }}
                        </span>
                    </div>
                </div>
                <flux:error name="foto_perfil" class="mt-2" />
            </div>
            <br>

            {{-- Inputs --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Rol --}}
                <div>
                    <flux:select name="rol" label="Rol del usuario (*)" required>
                        @foreach ($roles as $rol)
                            <flux:select.option
                                value="{{ $rol->name }}"
                                :selected="old('rol', $usuario->getRoleNames()->first() ?? '') == $rol->name">
                                {{ $rol->name }}
                            </flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:error name="rol" />
                </div>

                {{-- Nombre --}}
                <div>
                    <flux:label>Nombre completo <span class="text-red-500">(*)</span></flux:label>
                    <flux:input name="name" icon="user" value="{{ old('name', $usuario->name) }}" required />
                    <flux:error name="name" />
                </div>

                {{-- Email --}}
                <div>
                    <flux:label>Correo electrónico <span class="text-red-500">(*)</span></flux:label>
                    <flux:input name="email" type="email" icon="envelope" value="{{ old('email', $usuario->email) }}" required />
                    <flux:error name="email" />
                </div>

                {{-- Estado --}}
                <div>
                    <flux:select name="estado" label="Estado (*)" required>
                        <flux:select.option value="Activo" :selected="old('estado', $usuario->estado) == 'Activo'">
                            Activo
                        </flux:select.option>
                        <flux:select.option value="Inactivo" :selected="old('estado', $usuario->estado) == 'Inactivo'">
                            Inactivo
                        </flux:select.option>
                    </flux:select>
                    <flux:error name="estado" />
                </div>

                {{-- Contraseñas --}}
                <div>
                    <flux:label>Contraseña <span class="text-xs text-gray-400">(Opcional)</span></flux:label>
                    <flux:input name="password" type="password" icon="key" placeholder="Dejar en blanco para mantener" />
                    <flux:error name="password" />
                </div>
                <div>
                    <flux:label>Confirmar Contraseña <span class="text-xs text-gray-400">(Opcional)</span></flux:label>
                    <flux:input name="password_confirmation" type="password" icon="key" placeholder="Repita la contraseña" />
                    <flux:error name="password_confirmation" />
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <flux:button href="{{ route('admin.usuarios.index') }}">Cancelar</flux:button>
                <flux:button type="submit" variant="primary" class="px-5 cursor-pointer" color="green">Actualizar Usuario</flux:button>
            </div>
        </div>
    </form>

    <script>
        (function() {
            const actualBtn = document.getElementById('foto-input');
            const fileChosen = document.getElementById('file-chosen');
            const preview = document.getElementById('image-preview');
            const placeholderIcon = document.getElementById('placeholder-icon');

            actualBtn.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    fileChosen.textContent = file.name;
                    fileChosen.classList.add('text-indigo-600', 'font-medium');
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.classList.remove('hidden');
                        placeholderIcon.classList.add('hidden');
                    }
                    reader.readAsDataURL(file);
                }
            });
        })();
    </script>
</x-layouts::app>
