<?php

namespace App\Livewire\Tickets;

use Livewire\Component;

class Service extends Component
{
    public ?int $ticketId = null;
    public array $serviceData = [];

    public function mount(?int $ticketId = null)
    {
        $this->ticketId = $ticketId;
    }
    
    public function loadServiceData()
    {
        if (!$this->ticketId) {
            return;
        }

        sleep(rand(1,8));
        
        $this->serviceData = [
            'service_name' => 'Sample Service',
            'service_type' => 'Premium',
            'status' => 'Active'
        ];
    }

    public function render()
    {
        return view('livewire.tickets.service');
    }
}
