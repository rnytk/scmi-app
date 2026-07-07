<?php

namespace App\Livewire\Packages;

use Livewire\Component;
use App\Models\Package;

class TrackPackage extends Component
{
    public Package $package;

    public function mount(Package $package)
    {
        // Cargamos la información necesaria para el rastreo
        $this->package = $package->load(['originAgency', 'destinationAgency', 'recipient']);
    }

    public function render()
    {
        return view('livewire.packages.track-package');
    }
}