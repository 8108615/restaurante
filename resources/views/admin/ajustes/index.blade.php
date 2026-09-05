<x-layouts::app title="Ajustes del Sistema">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Ajustes deL Sistema</flux:heading>
        <br>
        <flux:separator variant="subtle" />
    </div>

    <div class="bg-white dark:bg-neutral-800 border-t border-gray-200 dark:border-gray-700 rounded-lg shadow-lg transition-all duration-300 hover:shadow-xl">
        <form action="{{ url('/admin/ajustes') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Body --}}
            <div class="p-6">

                {{-- Formulario en grid responsivo: 1 columna en móvil, 2 en md+ --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <flux:label>Nombre de la Empresa<span class="text-red-500" title="Campo obligatorio">
                                (*)</span></flux:label>
                        <flux:input name="nombre" icon="building-office"
                            value="{{ old('nombre', $ajuste->nombre ?? '') }}" placeholder="Nombre de la Empresa"
                            required />
                        <flux:error name="nombre" />
                    </div>

                    <div class="mb-4">
                        <flux:label>Descripción</flux:label>
                        <flux:input name="descripcion" value="{{ old('descripcion', $ajuste->descripcion ?? '') }}"
                            icon="document-text" placeholder="Breve Descripcion de la Empresa..." />
                        <flux:error name="descripcion" />
                    </div>

                    <div class="mb-4">
                        <flux:label>Dirección <span class="text-red-500" title="Campo obligatorio">
                                (*)</span></flux:label>
                        <flux:input name="direccion" icon="map-pin"
                            value="{{ old('direccion', $ajuste->direccion ?? '') }}"
                            placeholder="Dirección de la Empresa" required />
                        <flux:error name="direccion" />
                    </div>

                    <div class="mb-4">
                        <flux:label>Teléfono <span class="text-red-500" title="Campo obligatorio">
                                (*)</span></flux:label>
                        <flux:input name="telefono" icon="phone"
                            value="{{ old('telefono', $ajuste->telefono ?? '') }}" placeholder="(591) 700-00-001"
                            required />
                        <flux:error name="telefono" />
                    </div>

                    <div class="mb-4">
                        <flux:label>Tipo de Moneda <span class="text-red-500" title="Campo obligatorio">(*)</span></flux:label>
                        <flux:select placeholder="Selecciona el tipo de Moneda..." name="divisa" required>
                            @foreach ($divisas as $codigo => $info)
                                <flux:select.option
                                    value="{{ $codigo }}"
                                    :selected="old('divisa', $ajuste->divisa ?? '') == $codigo">
                                    {{ $info['symbol'] }} - {{ $info['name'] }}
                                </flux:select.option>
                            @endforeach
                        </flux:select>
                    </div>

                    <div class="mb-4">
                        <flux:label>Correo Electronico <span class="text-red-500" title="Campo obligatorio">
                                (*)</span></flux:label>
                        <flux:input name="email" type="email" value="{{ old('email', $ajuste->email ?? '') }}"
                            icon="envelope" placeholder="Correo Electronico" required />
                        <flux:error name="email" />
                    </div>

                    <div class="mb-4">
                        <flux:label>Sitio Web</flux:label>
                        <flux:input name="web" icon="globe-alt" value="{{ old('web', $ajuste->web ?? '') }}"
                            placeholder="www.empresa.com" />
                        <flux:error name="web" />
                    </div>

                    <div class="mb-4">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            @if ($ajuste->logo ?? false)
                                <div class="mb-4">
                                    <flux:label>Logo Actual</flux:label>

                                    <div class="flex items-center gap-6">
                                        <img src="{{ asset('storage/' . $ajuste->logo) }}" width="50%"
                                            alt="">
                                    </div>
                                </div>
                            @endif

                            <div class="mb-4">
                                <flux:label>Logo de la Empresa</flux:label>

                                <div class="flex items-center gap-6">
                                    <div class="relative group">
                                        <div
                                            class="h-20 w-20 rounded-2xl border-2 border-slate-100 overflow-hidden bg-slate-50 flex items-center justify-center shadow-inner">
                                            <img id="image-preview" src="#" alt="Preview"
                                                class="hidden h-full w-full object-cover">
                                            <flux:icon id="placeholder-icon" name="photo"
                                                class="text-slate-300 h-8 w-8" />
                                        </div>

                                    </div>

                                    <div class="flex flex-col gap-2">
                                        <div class="flex items-center gap-3">
                                            <input type="file" name="logo" id="logo-input" class="hidden"
                                                accept="image/*">

                                            <label for="logo-input"
                                                class="cursor-pointer flex items-center gap-2 px-6 py-3 bg-white border-2 border-slate-200 rounded-2xl text-slate-600 font-bold hover:border-indigo-500 hover:text-indigo-600 hover:bg-indigo-50 transition-all shadow-sm">
                                                <flux:icon name="cloud-arrow-up" variant="micro" />
                                                <span>Seleccionar Logo</span>
                                            </label>
                                        </div>

                                        <span id="file-chosen" class="text-sm text-slate-400 italic ml-1">Ningún
                                            archivo
                                            seleccionado</span>
                                    </div>
                                </div>

                                <flux:error name="logo" />
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            {{-- Footer --}}
            <div
                class="bg-gray-50 dark:bg-neutral-700 border-t border-gray-200 dark:border-gray-700 rounded-b-lg p-6 text-left">
                <div class="flex space-x-3">
                    <a href="{{ route('admin.ajustes.index') }}"
                        class="px-5  text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none
                        focus:ring-2 focus:ring-gray-200 focus:ring-offset-1 transition-all duration-200 inline-flex items-center">
                        <i class="fas fa-times mr-2"></i>
                        Cancelar
                    </a>
                    <flux:button variant="primary" type="submit" class="px-5 cursor-pointer" color="blue">
                        <i class="fas fa-save mr-2"></i> Guardar
                    </flux:button>

                </div>
            </div>
        </form>
    </div>

    <script>
        (function() {
            const actualBtn = document.getElementById('logo-input');
            if (!actualBtn) return;
            // prevent re-initialization when Livewire swaps DOM
            if (actualBtn.dataset.logoInitialized) return;
            actualBtn.dataset.logoInitialized = '1';

            const fileChosen = document.getElementById('file-chosen');
            const preview = document.getElementById('image-preview');
            const placeholderIcon = document.getElementById('placeholder-icon');

            actualBtn.addEventListener('change', function() {
                const file = this.files[0];

                if (file) {
                    // Actualizar texto del nombre
                    fileChosen.textContent = file.name;
                    fileChosen.classList.remove('text-slate-400');
                    fileChosen.classList.add('text-indigo-600', 'font-medium');

                    // Lógica de Previsualización
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
