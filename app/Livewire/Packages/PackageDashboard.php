<?php

namespace App\Livewire\Packages;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Package;
use Illuminate\Support\Facades\Auth;

class PackageDashboard extends Component
{
    use WithPagination;

    public $search = '';
    public $context = 'outbox'; // Contexto por defecto: outbox, reception, route

    // Identificamos el submenú activo mediante el nombre de la ruta
    public function mount()
    {
        $routeName = request()->route()->getName();

        if (str_contains($routeName, 'reception')) {
            $this->context = 'reception';
        } elseif (str_contains($routeName, 'route')) {
            $this->context = 'route';
        } else {
            $this->context = 'outbox';
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $userId = Auth::id(); // Obtenemos quién está logueado

        // 1. Iniciamos la consulta base
        $query = Package::with(['packageType', 'originAgency', 'destinationAgency', 'recipient']);

        // 2. APLICAMOS EL SCOPING SEGÚN EL SUBMENÚ SELECCIONADO
        if ($this->context === 'reception') {
            // Mis Recepciones: Paquetes donde soy el destinatario
            $query->where('recipient_id', $userId);
            
        } elseif ($this->context === 'route') {
            // Mi Ruta Asignada: Paquetes asignados a mí o bajo mi custodia física
            $query->where(function ($q) use ($userId) {
                $q->where('assigned_messenger_id', $userId)
                  ->orWhere('current_custodian_id', $userId); //Pendiente: Asegurarse de que se aplique ese scope  o no 
            });
            
        } else {
            // Envíos Realizados: Paquetes creados por mí
            $query->where('sender_id', $userId);
        }

        // 3. Aplicamos el buscador sobre los resultados ya filtrados
        $query->when($this->search, function ($q) {
            $q->where(function ($subQuery) {
                $subQuery->where('description', 'ilike', '%' . $this->search . '%')
                         ->orWhere('id', (int) $this->search);
            });
        });

        // 4. Ejecutamos la consulta paginada
        $packages = $query->latest()->paginate(10);

        // Título dinámico para la interfaz
        $pageTitle = match($this->context) {
            'reception' => 'Mis Recepciones',
            'route' => 'Mi Ruta Asignada',
            default => 'Envíos Realizados',
        };

        return view('livewire.packages.package-dashboard', [
            'packages' => $packages,
            'pageTitle' => $pageTitle
        ]);
    }
}