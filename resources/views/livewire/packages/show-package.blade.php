<div class="min-h-screen bg-gradient-to-br from-[#f0f4f8] to-[#e6f0d8]/50 py-12 px-4 sm:px-6 relative overflow-hidden">
    
    <div class="max-w-2xl mx-auto relative z-10">
        
        <div class="bg-white rounded-[2rem] shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
            
            <div class="flex justify-center mt-10">
                <div class="bg-[#84bd00]/10 p-3 rounded-full flex items-center justify-center">
                    <div class="bg-[#84bd00] text-white p-3 rounded-full shadow-md shadow-[#84bd00]/30">
                        <flux:icon.check class="w-8 h-8 stroke-[3]" />
                    </div>
                </div>
            </div>

            <div class="text-center mt-6 px-8">
                <h1 class="text-3xl font-black text-[#071d49] tracking-tight">¡Envío Registrado con Éxito!</h1>
                <p class="text-gray-500 mt-2 text-sm font-medium">El paquete ya está en el sistema de logística de Kato-Ki.</p>
            </div>

            <div class="bg-gray-50 mx-8 mt-8 rounded-2xl p-5 flex flex-col sm:flex-row justify-between items-center border border-gray-100">
                <span class="text-xs font-bold text-gray-500 tracking-widest uppercase mb-1 sm:mb-0">ID del Paquete</span>
                <span class="text-2xl font-black text-[#4c8c2b]">#PKG-{{ str_pad($package->id, 4, '0', STR_PAD_LEFT) }}</span>
            </div>

            <div class="mx-8 mt-8 grid grid-cols-2 gap-y-8 gap-x-4">
                <div>
                    <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Remitente</div>
                    <div class="font-bold text-gray-900 text-base">{{ $package->sender->name ?? 'N/A' }}</div>
                    <div class="text-xs text-gray-500 flex items-center mt-1">
                        <flux:icon.building-office class="w-3 h-3 mr-1" />
                        {{ $package->originAgency->name ?? 'N/A' }}
                    </div>
                </div>

                <div>
                    <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Destinatario</div>
                    <div class="font-bold text-gray-900 text-base">{{ $package->recipient->name ?? 'N/A' }}</div>
                </div>

                <div>
                    <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Destino</div>
                    <div class="font-bold text-[#071d49] text-base flex items-start">
                        <flux:icon.map-pin class="w-4 h-4 mr-1 text-[#84bd00] shrink-0" />
                        {{ $package->destinationAgency->name ?? 'N/A' }}
                    </div>
                </div>

                <div>
                    <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Referencia / Tipo</div>
                    <div class="font-bold text-gray-900 text-base">{{ $package->packageType->name ?? 'N/A' }}</div>
                    <div class="text-xs text-gray-500 mt-1 truncate pr-4">{{ $package->description ?? 'Sin descripción' }}</div>
                </div>
            </div>

            <div class="mx-8 mt-10 mb-8 pt-6 border-t border-dashed border-gray-200 flex flex-col sm:flex-row justify-between items-center gap-6">
                
                <div class="flex items-center gap-4">
                    <div class="bg-gray-900 p-1.5 rounded-xl shadow-inner">
                        <div class="bg-white p-1 rounded-lg">
                            {!! $qrCode !!}
                        </div>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Escanea para seguimiento</div>
                        <div class="text-xs font-mono font-bold text-gray-700 tracking-wider">LOG-SCMI-{{ date('Y') }}-{{ $package->id }}</div>
                    </div>
                </div>

                <div class="text-center sm:text-right">
                    <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Fecha de Registro</div>
                    <div class="text-sm font-bold text-gray-900">{{ $package->created_at->format('d M, Y') }}</div>
                </div>
            </div>
            
            <div class="w-full h-2 bg-repeat-x opacity-20" style="background-image: radial-gradient(circle, #000 2px, transparent 2.5px); background-size: 12px 12px;"></div>
        </div>

        <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-4 px-4 sm:px-0">
            <button wire:click="downloadPdf" wire:loading.attr="disabled"
    class="bg-[#84bd00] hover:bg-[#4c8c2b] text-white rounded-2xl py-4 flex items-center justify-center font-bold text-[15px] transition-all shadow-lg shadow-[#84bd00]/30 active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed">
    
    <flux:icon.arrow-down-tray wire:loading.remove wire:target="downloadPdf" class="w-5 h-5 mr-2 stroke-[2.5]" /> 
    
    <span wire:loading.remove wire:target="downloadPdf">Descargar Guía PDF</span>
    <span wire:loading wire:target="downloadPdf" class="flex items-center">
        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Generando Documento...
    </span>
</button>
            
            <button class="bg-white border-2 border-[#071d49] text-[#071d49] hover:bg-gray-50 rounded-2xl py-4 flex items-center justify-center font-bold text-[15px] transition-all active:scale-[0.98]">
                <flux:icon.printer class="w-5 h-5 mr-2 stroke-[2.5]" /> 
                Imprimir Etiqueta
            </button>
        </div>

        <div class="mt-10 text-center pb-12">
            <a href="{{ route('packages.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-[#071d49] transition-colors">
                <flux:icon.arrow-left class="w-4 h-4 mr-2" />
                Volver al Dashboard
            </a>
        </div>
    </div>
<--- Notification -->
    <div class="fixed bottom-6 right-6 bg-[#071d49] rounded-2xl shadow-2xl p-4 flex items-center gap-4 border border-white/10 z-50 animate-fade-in-up">
        <div class="bg-[#84bd00] p-1.5 rounded-full">
            <flux:icon.check class="w-4 h-4 text-white stroke-[3]" />
        </div>
        <div>
            <div class="text-white text-sm font-bold">Sistema SCMI Actualizado</div>
            <div class="text-blue-200/70 text-xs">Envío sincronizado{{ $package->destinationAgency->name ?? 'Destino' }}.</div>
        </div>
    </div>
</div>

<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
        animation-delay: 0.5s;
        opacity: 0;
    }
</style>