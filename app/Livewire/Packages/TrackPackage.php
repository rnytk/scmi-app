<?php

namespace App\Livewire\Packages;

use Livewire\Component;
use App\Models\Package;
use App\Models\CustodyHistory;
use Illuminate\Support\Facades\Auth;
use Flux\Flux; 

class TrackPackage extends Component
{
    public Package $package;

    public function mount(Package $package)
    {
        $this->package = $package->load(['originAgency', 'destinationAgency', 'recipient']);
    }

    public function takeCustody()
    {
        $user = Auth::user();

        // 1. Evitar auto-escaneo o paquetes ya entregados
        if ($this->package->status->value === 'delivered' || $this->package->current_custodian_id === $user->id) {
            return;
        }

        $currentStatus = $this->package->status->value;

        // ==========================================
        // 2. CERRADURAS DE SEGURIDAD (Las Reglas Estrictas)
        // ==========================================

        // Regla A: Si Wendy lo tiene (Origen), SOLO el Mensajero Asignado puede llevárselo.
        if ($currentStatus === 'in_origin_reception') {
            if ($user->id !== $this->package->assigned_messenger_id) {
                Flux::toast(
                    text: 'Acceso Denegado',
                    heading: 'Solo el mensajero asignado puede recolectar este paquete.',
                    variant: 'danger'
                );
                return;
            }
        }

        // Regla B: Si Jimena lo tiene (Destino), SOLO el Destinatario Final puede reclamarlo.
        if ($currentStatus === 'in_destination_reception') {
            if ($user->id !== $this->package->recipient_id) {
                Flux::toast(
                    text: 'Acceso Denegado',
                    heading: 'Solo el destinatario final puede retirar este envío.',
                    variant: 'danger'
                );
                return;
            }
        }

        // Regla C: Si el mensajero va en tránsito, validamos quién lo recibe en destino
        if ($currentStatus === 'in_transit') {
            if ($user->id !== $this->package->recipient_id && $user->branch_id !== $this->package->destination_branch_id) {
                Flux::toast(
                    text: 'Acceso Denegado',
                    heading: 'No perteneces a la agencia de destino asignada.',
                    variant: 'danger'
                );
                return;
            }
        }

        // ==========================================
        // 3. FLUJO LÓGICO DE ESTADOS
        // ==========================================
        $newStatus = match($currentStatus) {
            'created' => 'in_origin_reception',
            'in_origin_reception' => 'in_transit',
            'in_transit' => ($user->id === $this->package->recipient_id) ? 'delivered' : 'in_destination_reception',
            'in_destination_reception' => 'delivered',
            default => $currentStatus,
        };

        // ==========================================
        // 4. ESCRIBIR EN LA BITÁCORA (NOMBRES CORREGIDOS)
        // ==========================================
        CustodyHistory::create([
            'package_id' => $this->package->id,
            'handed_over_by_id' => $this->package->current_custodian_id, // Quién lo tenía
            'received_by_id' => $user->id,                                // Quién escaneó
            'resulting_status' => $newStatus, 
            'scanned_at' => now(),                                        // Fecha y hora
        ]);

        // ==========================================
        // 5. ACTUALIZAR LA TABLA PRINCIPAL
        // ==========================================
        $this->package->update([
            'current_custodian_id' => $user->id,
            'status' => $newStatus,
        ]);

        $this->package->refresh();
        
        // Notificamos éxito visualmente
        Flux::toast(
            text: 'Operación Exitosa',
            heading: 'Has tomado la custodia del paquete.',
            variant: 'success'
        );
    }

    public function render()
    {
        return view('livewire.packages.track-package');
    }
}