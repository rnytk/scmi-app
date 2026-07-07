<?php

namespace App\Livewire\Packages;

use Livewire\Component;
use App\Models\Package;

// 1. Eliminamos el "use" de SimpleSoftwareIO y agregamos los de BaconQrCode
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class ShowPackage extends Component
{
    public Package $package;

    // Al cargar la vista, Laravel busca el paquete por su ID automáticamente
    public function mount(Package $package)
    {
        // Cargamos las relaciones para no saturar la base de datos
        $this->package = $package->load(['packageType', 'originAgency', 'destinationAgency', 'sender', 'recipient', 'assignedMessenger']);
    }

   public function render()
    {
      
       
        $qrPayload = route('packages.track', $this->package->id);

        // 2. EL TAMAÑO (Aumentamos de 60 a 150 píxeles y le damos 1px de margen)
        $renderer = new ImageRenderer(
            new RendererStyle(150, 1),
            new SvgImageBackEnd()
        );
        
        $writer = new Writer($renderer);
        $qrCode = $writer->writeString($qrPayload);

        return view('livewire.packages.show-package', [
            'qrCode' => $qrCode
        ]);
    }
}