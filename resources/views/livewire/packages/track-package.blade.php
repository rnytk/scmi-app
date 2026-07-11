<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 relative">
    <div class="max-w-md mx-auto">
        
        <div class="text-center mb-8">
            <h1 class="text-2xl font-black text-[#071d49]">Rastreo de Envío</h1>
            <p class="text-gray-500 text-sm mt-1">Sistema de Mensajería Interna</p>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-6">
            
            <div class="bg-[#071d49] p-6 text-center text-white">
                <span class="text-blue-200/80 text-xs font-bold uppercase tracking-widest block mb-1">Tracking ID</span>
                <span class="text-3xl font-black tracking-wider">#PKG-{{ str_pad($package->id, 4, '0', STR_PAD_LEFT) }}</span>
            </div>

            <div class="p-6 text-center">
                <div class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Estado Actual</div>
                
                @php
                    $statusConfig = match($package->status->value) {
                        'created' => ['icon' => 'cube', 'color' => 'text-gray-500', 'bg' => 'bg-gray-100'],
                        'in_origin_reception' => ['icon' => 'building-storefront', 'color' => 'text-[#f9c510]', 'bg' => 'bg-yellow-50'],
                        'in_transit' => ['icon' => 'truck', 'color' => 'text-blue-600', 'bg' => 'bg-blue-50'],
                        'in_destination_reception' => ['icon' => 'building-office-2', 'color' => 'text-lime-600', 'bg' => 'bg-lime-50'],
                        'delivered' => ['icon' => 'check-badge', 'color' => 'text-[#4c8c2b]', 'bg' => 'bg-green-50'],
                        default => ['icon' => 'clock', 'color' => 'text-gray-500', 'bg' => 'bg-gray-100'],
                    };
                @endphp

                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full {{ $statusConfig['bg'] }} mb-4">
                    <flux:icon dynamic :name="$statusConfig['icon']" class="w-10 h-10 {{ $statusConfig['color'] }}" />
                </div>
                
                <h2 class="text-2xl font-bold text-gray-900">{{ $package->status->label() }}</h2>
                
                <div class="mt-6 pt-6 border-t border-gray-100 flex justify-between items-center text-left">
                    <div>
                        <div class="text-[10px] font-bold text-gray-400 uppercase">Origen</div>
                        <div class="font-semibold text-[#071d49] text-sm">{{ $package->originAgency->name ?? 'N/A' }}</div>
                    </div>
                    <flux:icon.arrow-right class="w-4 h-4 text-gray-300 mx-2 shrink-0" />
                    <div class="text-right">
                        <div class="text-[10px] font-bold text-gray-400 uppercase">Destino</div>
                        <div class="font-semibold text-[#071d49] text-sm">{{ $package->destinationAgency->name ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 text-center">
            <p class="text-sm text-gray-500 mb-4">Escaneado el {{ now()->format('d M, Y h:i A') }}</p>
            
            <a href="{{ route('packages.index') }}" class="inline-flex items-center justify-center w-full px-5 py-3 text-sm font-bold text-[#071d49] bg-gray-50 hover:bg-gray-100 rounded-xl transition-colors">
                Ir a mi Dashboard
            </a>
        </div>

    </div>

    @auth
        @if($package->status->value !== 'delivered' && $package->current_custodian_id !== auth()->id())
            <div class="fixed bottom-0 left-0 w-full p-6 bg-gradient-to-t from-black via-black/95 to-transparent z-50">
                
                <flux:button 
                    wire:click="takeCustody"
                    wire:loading.attr="disabled"
                    class="w-full bg-[#84bd00] hover:bg-[#95d600] text-black font-bold text-lg py-5 rounded-2xl shadow-[0_0_25px_rgba(132,189,0,0.3)] transition-all flex justify-center items-center"
                >
                    <span wire:loading.remove wire:target="takeCustody" class="flex items-center gap-2">
                        <flux:icon.qr-code class="w-6 h-6" />
                        Aceptar Custodia
                    </span>
                    
                    <span wire:loading wire:target="takeCustody" class="flex items-center gap-2">
                        <flux:icon.arrow-path class="w-6 h-6 animate-spin" />
                        Validando...
                    </span>
                </flux:button>
                
            </div>
            
            <div class="h-32 w-full"></div>
        @endif
    @endauth
</div>