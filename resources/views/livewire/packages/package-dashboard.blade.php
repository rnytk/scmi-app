<div class="max-w-7xl mx-auto py-8">
    
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-brand-primary">{{ $pageTitle }}</h1>
            <p class="text-[#63666a] mt-1">Supervisión y control de logística.</p>
        </div>
        
        <a href="{{ route('packages.create') }}" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-white bg-[#84bd00] hover:bg-[#4c8c2b] rounded-lg shadow-sm transition-colors">
            <flux:icon.plus class="w-4 h-4 mr-2" />
            Nuevo Envío
        </a>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-2 sm:p-6">
        
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between mb-6 gap-4 px-2">
            
         <div class="flex flex-wrap items-center gap-2">
                <button wire:click="$set('statusFilter', 'all')" 
                    class="px-5 py-2 text-sm font-medium rounded-full shadow-sm transition-colors {{ $statusFilter === 'all' ? 'text-white bg-[#071d49]' : 'text-[#63666a] bg-gray-100 hover:bg-gray-200' }}">
                    Todos
                </button>
                
                <button wire:click="$set('statusFilter', 'in_transit')" 
                    class="px-5 py-2 text-sm font-medium rounded-full shadow-sm transition-colors {{ $statusFilter === 'in_transit' ? 'text-white bg-[#071d49]' : 'text-[#63666a] bg-gray-100 hover:bg-gray-200' }}">
                    En Tránsito
                </button>
                
                <button wire:click="$set('statusFilter', 'delivered')" 
                    class="px-5 py-2 text-sm font-medium rounded-full shadow-sm transition-colors {{ $statusFilter === 'delivered' ? 'text-white bg-[#071d49]' : 'text-[#63666a] bg-gray-100 hover:bg-gray-200' }}">
                    Entregado
                </button>
                
                <button wire:click="$set('statusFilter', 'pending')" 
                    class="px-5 py-2 text-sm font-medium rounded-full shadow-sm transition-colors {{ $statusFilter === 'pending' ? 'text-white bg-[#071d49]' : 'text-[#63666a] bg-gray-100 hover:bg-gray-200' }}">
                    Pendiente
                </button>
            </div>

            <div class="flex items-center gap-3 w-full lg:w-auto">
                <div class="relative w-full lg:w-48">
                    <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass" placeholder="Buscar ID..." class="!rounded-lg" />
                </div>
                <button class="p-2.5 text-gray-500 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-lg transition-colors">
                    <flux:icon.funnel class="w-5 h-5" />
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-xs font-bold tracking-wider text-gray-500 uppercase border-b border-gray-100">
                        <th class="px-4 py-4">ID de Paquete</th>
                        <th class="px-4 py-4">Destinatario</th>
                        <th class="px-4 py-4">Estado</th>
                        <th class="px-4 py-4">Fecha Registro</th>
                        <th class="px-4 py-4">Ruta</th>
                        <th class="px-4 py-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($packages as $package)
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="font-bold text-[#071d49]">#PKG-{{ str_pad($package->id, 4, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="font-bold text-[#071d49]">{{ $package->recipient->name ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">{{ $package->destinationAgency->name ?? 'Agencia Desconocida' }}</div>
                            </td>
                            
                            <td class="px-4 py-4 whitespace-nowrap">
                                @php
                                    $statusColor = match($package->status->value) {
                                        'created' => ['bg' => 'bg-gray-50', 'text' => 'text-gray-600', 'dot' => 'bg-gray-400'],
                                        'in_origin_reception' => ['bg' => 'bg-yellow-50', 'text' => 'text-yellow-700', 'dot' => 'bg-[#f9c510]'],
                                        'in_transit' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'dot' => 'bg-blue-500'],
                                        'in_destination_reception' => ['bg' => 'bg-lime-50', 'text' => 'text-lime-700', 'dot' => 'bg-lime-500'],
                                        'delivered' => ['bg' => 'bg-green-50', 'text' => 'text-green-700', 'dot' => 'bg-[#4c8c2b]'],
                                        default => ['bg' => 'bg-yellow-50', 'text' => 'text-yellow-700', 'dot' => 'bg-[#f9c510]'],
                                    };
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $statusColor['bg'] }} {{ $statusColor['text'] }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $statusColor['dot'] }} mr-2"></span>
                                    {{ $package->status->label() ?? 'Pendiente' }}
                                </span>
                            </td>
                            
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-[#63666a]">
                                {{ $package->created_at->format('d M, Y') }}
                            </td>

                            <td class="px-4 py-4 whitespace-nowrap text-sm text-[#63666a]">
                                Desde: {{ $package->originAgency->name ?? 'N/A' }}
                            </td>

                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                <flux:dropdown position="bottom-end">
                                    <flux:button variant="ghost" icon="ellipsis-vertical" size="sm" class="text-gray-400 hover:text-[#071d49]" />
                                    <flux:menu>
                                        @if($context === 'reception')
                                            <flux:menu.item icon="eye">Ver detalles</flux:menu.item>
                                            <flux:menu.item icon="check-circle" class="text-green-600 font-medium">Confirmar Recepción</flux:menu.item>
                                            <flux:menu.item icon="exclamation-triangle" class="text-amber-600">Reportar Incidencia</flux:menu.item>
                                        @elseif($context === 'route')
                                            <flux:menu.item icon="eye">Ver detalles</flux:menu.item>
                                            <flux:menu.item icon="qr-code">Escanear QR / Recolectar</flux:menu.item>
                                            <flux:menu.item icon="map-pin">Ver Destino</flux:menu.item>
                                        @else
                                            <flux:menu.item icon="eye">Ver detalles</flux:menu.item>
                                            <flux:menu.item icon="qr-code">Generar QR</flux:menu.item>
                                            <flux:menu.item icon="document-text">Historial</flux:menu.item>
                                        @endif
                                    </flux:menu>
                                </flux:dropdown>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-gray-500">
                                <flux:icon.inbox class="w-12 h-12 mx-auto mb-3 text-gray-300" />
                                <p class="text-lg font-medium text-[#071d49]">No hay envíos registrados</p>
                                <p class="text-sm">Los paquetes correspondientes a esta bandeja aparecerán aquí.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($packages->hasPages())
            <div class="px-4 py-4 border-t border-gray-100 mt-2">
                {{ $packages->links() }}
            </div>
        @endif
    </div>
</div>