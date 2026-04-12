<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Service;

class Settings extends Component
{
    public string $activeTab = 'services';

    // Service form
    public bool $showServiceModal = false;
    public bool $editingService = false;
    public ?int $editServiceId = null;
    public string $serviceName = '';
    public string $serviceType = 'regular';
    public float $pricePerKilo = 0;
    public float $washPrice = 0;
    public int $washMinutes = 0;
    public float $dryPrice = 0;
    public int $dryMinutes = 0;
    public float $foldPrice = 0;
    public float $minimumKilos = 5;

    public function openAddService()
    {
        $this->resetServiceForm();
        $this->showServiceModal = true;
    }

    public function openEditService($id)
    {
        $service = Service::find($id);
        if (!$service) return;

        $this->editingService = true;
        $this->editServiceId = $service->id;
        $this->serviceName = $service->name;
        $this->serviceType = $service->type;
        $this->pricePerKilo = $service->price_per_kilo;
        $this->washPrice = $service->wash_price;
        $this->washMinutes = $service->wash_minutes;
        $this->dryPrice = $service->dry_price;
        $this->dryMinutes = $service->dry_minutes;
        $this->foldPrice = $service->fold_price;
        $this->minimumKilos = $service->minimum_kilos;
        $this->showServiceModal = true;
    }

    public function saveService()
    {
        $this->validate([
            'serviceName' => 'required|min:2',
            'serviceType' => 'required',
            'pricePerKilo' => 'required|numeric|min:0',
            'minimumKilos' => 'required|numeric|min:1',
        ]);

        $data = [
            'name' => $this->serviceName,
            'type' => $this->serviceType,
            'price_per_kilo' => $this->pricePerKilo,
            'wash_price' => $this->washPrice,
            'wash_minutes' => $this->washMinutes,
            'dry_price' => $this->dryPrice,
            'dry_minutes' => $this->dryMinutes,
            'fold_price' => $this->foldPrice,
            'minimum_kilos' => $this->minimumKilos,
        ];

        if ($this->editingService && $this->editServiceId) {
            Service::where('id', $this->editServiceId)->update($data);
            $msg = 'Service updated!';
        } else {
            Service::create($data);
            $msg = 'Service created!';
        }

        $this->showServiceModal = false;
        $this->resetServiceForm();
        $this->dispatch('toast', message: $msg, type: 'success');
    }

    public function toggleService($id)
    {
        $service = Service::find($id);
        if ($service) {
            $service->update(['is_active' => !$service->is_active]);
            $this->dispatch('toast',
                message: $service->name . ' ' . ($service->is_active ? 'activated' : 'deactivated'),
                type: 'success'
            );
        }
    }

    public function deleteService($id)
    {
        Service::destroy($id);
        $this->dispatch('toast', message: 'Service deleted.', type: 'success');
    }

    private function resetServiceForm()
    {
        $this->editingService = false;
        $this->editServiceId = null;
        $this->serviceName = '';
        $this->serviceType = 'regular';
        $this->pricePerKilo = 0;
        $this->washPrice = 0;
        $this->washMinutes = 0;
        $this->dryPrice = 0;
        $this->dryMinutes = 0;
        $this->foldPrice = 0;
        $this->minimumKilos = 5;
    }

    public function render()
    {
        $services = Service::all();
        $user = auth()->user();

        return view('livewire.settings', compact('services', 'user'));
    }
}
