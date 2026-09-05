<x-layouts::app title="Detalles del Usuario">
    <div class="mb-6">
        <flux:breadcrumbs>
            
            <flux:breadcrumbs.item>Datos del usuario: {{ $usuario->name }}</flux:breadcrumbs.item>
        </flux:breadcrumbs>
    </div>

    <flux:card class="mb-6">
        <div class="flex items-center gap-4">
            <div class="h-20 w-20 rounded-full bg-zinc-100 flex items-center justify-center overflow-hidden border border-zinc-200">
                @if($usuario->foto_perfil)
                    <img src="{{ asset('storage/' . $usuario->foto_perfil) }}" alt="Foto" class="h-full w-full object-cover">
                @else
                    <flux:icon.user class="h-10 w-10 text-zinc-400" />
                @endif
            </div>

            <div>
                <flux:heading size="xl" class="uppercase text-blue-600">{{ $usuario->getRoleNames()->first() }}</flux:heading>
                <div class="flex items-center gap-3 mt-1">
                    <flux:badge size="sm">ID: #{{ $usuario->id }}</flux:badge>
                    <flux:badge color="{{ $usuario->estado == 'Inactivo' ? 'red' : 'green' }}" icon="{{ $usuario->estado == 'Inactivo' ? 'x-mark' : 'check' }}">
                        {{ $usuario->estado }}
                    </flux:badge>
                </div>
            </div>
        </div>
    </flux:card>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <flux:card>
            <flux:heading icon="map-pin" class="mb-4">Datos del Usuario</flux:heading>

            <div class="space-y-4">
                <div>
                    <flux:subheading>NOMBRE DE USUARIO</flux:subheading>
                    <flux:text>{{ $usuario->name }}</flux:text>
                </div>
                <div>
                    <flux:subheading>EMAIL</flux:subheading>
                    <flux:text>{{ $usuario->email }}</flux:text>
                </div>

                <div>
                    <flux:label>Fecha y hora de registro</flux:label>
                    <p class="mt-1 text-gray-800 dark:text-gray-200 flex items-center">
                        <i class="fas fa-clock mr-2 text-blue-500"></i>
                        {{ $usuario->created_at }}
                    </p>
                </div>

            </div>
        </flux:card>


    </div>

    <div class="mt-6">
        <flux:button href="{{ route('admin.usuarios.index') }}" icon="arrow-left">Volver al listado</flux:button>
    </div>
</x-layouts::app>
