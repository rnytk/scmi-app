<div class="min-h-screen bg-black flex flex-col relative overflow-hidden">
    
    <div class="absolute top-0 w-full z-50 bg-gradient-to-b from-black/80 to-transparent p-6 flex items-center justify-between">
        <a href="{{ route('packages.index') }}" class="text-white bg-white/20 p-2 rounded-full backdrop-blur-sm">
            <flux:icon.x-mark class="w-6 h-6" />
        </a>
        <h1 class="text-white font-bold text-lg tracking-wide">Escanear Paquete</h1>
        <div class="w-10"></div> </div>

    <div class="flex-1 flex items-center justify-center bg-black relative">
        <div id="reader" class="w-full max-w-sm mx-auto overflow-hidden rounded-3xl border-2 border-[#84bd00] shadow-[0_0_30px_rgba(132,189,0,0.3)]"></div>
        
        <div class="absolute inset-0 pointer-events-none flex items-center justify-center">
            <div class="w-64 h-64 border border-white/30 rounded-2xl relative">
                <div class="absolute top-0 left-0 w-8 h-8 border-t-4 border-l-4 border-[#84bd00] rounded-tl-xl"></div>
                <div class="absolute top-0 right-0 w-8 h-8 border-t-4 border-r-4 border-[#84bd00] rounded-tr-xl"></div>
                <div class="absolute bottom-0 left-0 w-8 h-8 border-b-4 border-l-4 border-[#84bd00] rounded-bl-xl"></div>
                <div class="absolute bottom-0 right-0 w-8 h-8 border-b-4 border-r-4 border-[#84bd00] rounded-br-xl"></div>
            </div>
        </div>
    </div>

    <div class="bg-[#111] rounded-t-3xl p-8 z-50">
        <div class="text-center">
            <flux:icon.qr-code class="w-10 h-10 text-[#84bd00] mx-auto mb-3" />
            <h2 class="text-white font-bold text-xl mb-1">Apunta al Código QR</h2>
            <p class="text-gray-400 text-sm">Ubica el código impreso en la viñeta dentro del recuadro para leer la información de logística.</p>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    
    <script>
        document.addEventListener('livewire:init', () => {
            
            const html5QrCode = new Html5Qrcode("reader");
            const config = { 
                fps: 10, 
                qrbox: { width: 250, height: 250 },
                aspectRatio: 1.0
            };

            const onScanSuccess = (decodedText) => {
                html5QrCode.stop().then(() => {
                    // Redirigimos a la URL escaneada
                    window.location.href = decodedText;
                }).catch(err => console.error(err));
            };

            // ESTRATEGIA NUEVA: Obtenemos el array de cámaras físicas primero
            Html5Qrcode.getCameras().then(devices => {
                if (devices && devices.length) {
                    // Seleccionamos el ID de la última cámara detectada (el lente principal)
                    const cameraId = devices[devices.length - 1].id;
                    
                    html5QrCode.start(
                        cameraId, 
                        config, 
                        onScanSuccess
                    ).catch(err => {
                        alert("El lente fue bloqueado por el sistema: " + err);
                    });
                } else {
                    alert("Tu navegador no reportó ninguna cámara física.");
                }
            }).catch(err => {
                alert("Error crítico de permisos en el navegador: " + err);
            });
            
        });
    </script>

    <style>
        #reader__dashboard_section_csr span { color: white !important; }
        #reader__dashboard_section_swaplink { display: none !important; }
        #reader video { object-fit: cover !important; border-radius: 1.5rem; }
    </style>
</div>