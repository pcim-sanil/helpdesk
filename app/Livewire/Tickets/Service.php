<?php

namespace App\Livewire\Tickets;

use Livewire\Component;

class Service extends Component
{
    public ?int $ticketId = null;
    public bool $isLoading = false;
    public array $serviceData = [];

    public function mount(?int $ticketId = null)
    {
        $this->ticketId = $ticketId;
        // Don't load data in mount - let it load lazily
    }
    
    public function loadServiceData()
    {
        if (!$this->ticketId) {
            return;
        }

        $this->isLoading = true;
        
        // Simulate data loading - replace with actual service data fetching
        sleep(2); // Simulate slow loading
        
        // Replace this with actual database query for service data
        $this->serviceData = [
            'service_name' => 'Sample Service',
            'service_type' => 'Premium',
            'status' => 'Active'
        ];
        
        $this->isLoading = false;
    }

    public function render()
    {
        return view('livewire.tickets.service');
    }
}
