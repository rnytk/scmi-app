<?php

namespace App\App\Livewire\Packages;

namespace App\Livewire\Packages;

use Livewire\Component;
use App\Models\Package;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Barryvdh\DomPDF\Facade\Pdf;

class ShowPackage extends Component
{
    public Package $package;

    public function mount(Package $package)
    {
        $this->package = $package->load(['packageType', 'originAgency', 'destinationAgency', 'sender', 'recipient', 'assignedMessenger']);
    }

    // GENERADOR DE CÓDIGO QR (Centralizado para reutilizarse)
    private function generateQrCode($size = 150)
    {
        $qrPayload = route('packages.track', $this->package->id);

        $renderer = new ImageRenderer(
            new RendererStyle($size, 1),
            new SvgImageBackEnd()
        );
        
        $writer = new Writer($renderer);
        return $writer->writeString($qrPayload);
    }

    public function render()
    {
        // Generamos el QR de 150px para la pantalla de éxito
        $qrCode = $this->generateQrCode(150);

        return view('livewire.packages.show-package', [
            'qrCode' => $qrCode
        ]);
    }

    // ACCIÓN DE DESCARGA DEL PDF
    // ACCIÓN DE DESCARGA DEL PDF
    public function downloadPdf()
    {
        // 1. Generamos el QR
        $rawSvg = $this->generateQrCode(150);

        // TRUCO PARA DOMPDF: Codificamos el SVG a texto Base64
        $qrCodeBase64 = base64_encode($rawSvg);

        // 2. Cargamos los datos en la vista PDF pasando el Base64
        $pdf = Pdf::loadView('packages.pdf', [
            'package' => $this->package,
            'qrCodeBase64' => $qrCodeBase64 // <-- Pasamos la nueva variable
        ]);

        // 3. Formateamos el nombre del archivo
        $fileName = 'Guia_PKG-' . str_pad($this->package->id, 4, '0', STR_PAD_LEFT) . '.pdf';

        // 4. Disparamos la descarga
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, $fileName);
    }
}