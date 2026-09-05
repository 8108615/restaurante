<x-layouts::app title="Registrar Nuevo Usuario">
    <div class="mb-6 w-full">
        <flux:heading size="xl">Nuevo Usuario</flux:heading>
        <flux:separator variant="subtle" class="my-4" />
    </div>

    <form action="{{ route('admin.usuarios.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="bg-white dark:bg-neutral-800 p-6 rounded-lg border border-gray-200 dark:border-gray-700">

            {{-- Foto de Perfil - Contenedor rígido --}}
            <div class="col-span-1 md:col-span-4 mb-8">
                <flux:label>Foto de Perfil</flux:label>
                <div class="flex items-center gap-6 mt-2">
                    <div class="h-20 w-20 flex-shrink-0 relative">
                        <div class="h-full w-full rounded-2xl border-2 border-slate-100 overflow-hidden bg-slate-50 flex items-center justify-center shadow-inner">
                            <img id="image-preview" src="#" alt="Preview" class="hidden h-full w-full object-cover">
                            <flux:icon id="placeholder-icon" name="photo" class="text-slate-300 h-8 w-8" />
                        </div>
                    </div>
                    <div class="flex flex-col gap-2">
                        <input type="file" name="foto_perfil" id="foto-input" class="hidden" accept="image/*">
                        <label for="foto-input" class="cursor-pointer flex items-center gap-2 px-6 py-3 bg-white border-2 border-slate-200 rounded-2xl text-slate-600 font-bold hover:border-indigo-500 transition-all">
                            <flux:icon name="cloud-arrow-up" variant="micro" />
                            <span>Seleccionar Foto</span>
                        </label>
                        <span id="file-chosen" class="text-sm text-slate-400 italic">Ningún archivo seleccionado</span>
                    </div>
                </div>
                <flux:error name="foto_perfil" class="mt-2" />
            </div>

            {{-- Inputs --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- Rol --}}
                <div>
                    <flux:select name="rol" label="Rol del usuario (*)" placeholder="Seleccione un Rol..." required>
                        @foreach ($roles as $rol)
                            <flux:select.option value="{{ $rol->name }}">
                                {{ $rol->name }}
                            </flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:error name="rol" />
                </div>

                {{-- Nombre --}}
                <div>
                    <flux:label>Nombre completo <span class="text-red-500">(*)</span></flux:label>
                    <flux:input name="name" icon="user" value="{{ old('name') }}" placeholder="Nombre y apellidos..." required />
                    <flux:error name="name" />
                </div>

                {{-- Email --}}
                <div>
                    <flux:label>Correo electrónico <span class="text-red-500">(*)</span></flux:label>
                    <flux:input name="email" type="email" icon="envelope" value="{{ old('email') }}" placeholder="correo@ejemplo.com" required />
                    <flux:error name="email" />
                </div>



                {{-- Estado --}}
                <div>
                    <flux:select name="estado" label="Estado (*)" required>
                        <flux:select.option value="Activo" selected>Activo</flux:select.option>
                        <flux:select.option value="Inactivo">Inactivo</flux:select.option>
                    </flux:select>
                    <flux:error name="estado" />
                </div>

                {{-- Contraseñas --}}
                <div>
                    <flux:label>Contraseña <span class="text-red-500">(*)</span></flux:label>
                    <flux:input name="password" type="password" icon="key" placeholder="Mínimo 8 caracteres" required />
                    <flux:error name="password" />
                </div>
                <div>
                    <flux:label>Confirmar Contraseña <span class="text-red-500">(*)</span></flux:label>
                    <flux:input name="password_confirmation" type="password" icon="key" placeholder="Repita la contraseña" required />
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <flux:button  href="{{ route('admin.usuarios.index') }}">Cancelar</flux:button>
                <flux:button type="submit" variant="primary" class="px-5 cursor-pointer" color="blue">Registrar Usuario</flux:button>
            </div>
        </div>
    </form>

    <script>
        (function() {
            const actualBtn = document.getElementById('foto-input');
            if (!actualBtn) return;
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
