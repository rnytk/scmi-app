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
    
    // 1. Declaramos la variable del filtro de estado
    public $statusFilter = 'all'; 

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

    // 2. Reseteamos la página cuando cambian de filtro para evitar tablas vacías
    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $userId = Auth::id(); // Obtenemos quién está logueado

        // Iniciamos la consulta base
        $query = Package::with(['packageType', 'originAgency', 'destinationAgency', 'recipient']);

        // APLICAMOS EL SCOPING SEGÚN EL SUBMENÚ SELECCIONADO
        if ($this->context === 'reception') {
            $query->where('recipient_id', $userId);
            
        } elseif ($this->context === 'route') {
            $query->where(function ($q) use ($userId) {
                $q->where('assigned_messenger_id', $userId)
                  ->orWhere('current_custodian_id', $userId); 
            });
            
        } else {
            $query->where('sender_id', $userId);
        }

        // 3. APLICAMOS EL FILTRO DE ESTADO
        if ($this->statusFilter === 'in_transit') {
            $query->where('status', 'in_transit');
        } elseif ($this->statusFilter === 'delivered') {
            $query->where('status', 'delivered');
        } elseif ($this->statusFilter === 'pending') {
            // Pendientes: los que apenas se crearon o aún están en la agencia origen
            $query->whereIn('status', ['created', 'in_origin_reception']);
        }

        // Aplicamos el buscador sobre los resultados ya filtrados
        $query->when($this->search, function ($q) {
            $q->where(function ($subQuery) {
                $subQuery->where('description', 'ilike', '%' . $this->search . '%')
                         ->orWhere('id', (int) $this->search);
            });
        });

        // Ejecutamos la consulta paginada
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