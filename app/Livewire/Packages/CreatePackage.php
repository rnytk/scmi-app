<?php

namespace App\Livewire\Packages;

use Livewire\Component;
use App\Models\{Package, PackageType, Agency, User};
use App\Enums\PackageStatus;
use Illuminate\Support\Facades\Auth;

class CreatePackage extends Component
{
    public $description = '';
    public $sender_id = '';
    public $package_type_id = '';
    public $origin_agency_id = '';
    public $destination_agency_id = '';
    public $recipient_id = '';
    public $assigned_messenger_id = '';


public function updatedDestinationAgencyId($value)
{
    $this->recipient_id = '';
}
    


    public function mount()
    {
        // Obtenemos los datos del usuario autenticado
        $user = Auth::user();

        if ($user) {
            // Asignamos el ID del usuario logueado al colaborador remitente
            $this->sender_id = $user->id;

            // Asignamos la agencia del usuario logueado a la agencia origen
            // (Nota: Esto asume que tu tabla de usuarios tiene una columna 'agency_id')
            $this->origin_agency_id = $user->agency_id;
        }
    }

    
    public function save()
    {
      
        $this->validate([
            'package_type_id' => 'required|exists:pgsql.package_types,id',
            'origin_agency_id' => 'required|exists:pgsql_sgci.agencies,id',
            'destination_agency_id' => 'required|exists:pgsql_sgci.agencies,id',
            'sender_id' => 'required|exists:pgsql_sgci.users,id',
            'recipient_id' => 'required|exists:pgsql_sgci.users,id',
            'assigned_messenger_id' => 'required|exists:pgsql_sgci.users,id',
            'description' => 'nullable|string|max:255',
        ]);

       
        Package::create([
            'package_type_id' => $this->package_type_id,
            'description' => $this->description,
            'origin_agency_id' => $this->origin_agency_id,
            'destination_agency_id' => $this->destination_agency_id,
            'sender_id' => $this->sender_id,
            'recipient_id' => $this->recipient_id,
            'assigned_messenger_id' => $this->assigned_messenger_id,
            'current_custodian_id' => Auth::id(),
            'status' => PackageStatus::Created,
        ]);

        session()->flash('message', 'Paquete registrado exitosamente.');
        return redirect()->route('packages.index');
    }

    public function render()
    {
        $destinationUsers = [];

    // Si el usuario ya seleccionó una agencia destino, filtramos en la base de datos
    if (!empty($this->destination_agency_id)) {
        $destinationUsers = User::where('is_active', true)
            ->where('agency_id', $this->destination_agency_id) // Filtramos por la agencia elegida
            ->orderBy('name')
            ->get();
    }

    return view('livewire.packages.create-package', [
        'packageTypes' => PackageType::where('is_active', true)->get(),
        'agencies' => Agency::where('is_active', true)->orderBy('name')->get(),
        'users' => $destinationUsers, // Pasamos la lista ya filtrada a la vista
        'messengers' => User::where('is_active', true)->orderBy('name')->get(),
    ]);
    }
}