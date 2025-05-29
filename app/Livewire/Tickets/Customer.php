<?php

namespace App\Livewire\Tickets;

use Livewire\Component;

class Customer extends Component
{
    public ?int $ticketId = null;
    public bool $isLoading = false;
    public array $customerData = [];

    public function mount(?int $ticketId = null)
    {
        $this->ticketId = $ticketId;
        // Don't load data in mount - let it load lazily
    }

    public function loadCustomerData()
    {
        if (!$this->ticketId) {
            return;
        }        
        // Simulate data loading - replace with actual customer data fetching
        sleep(1); // Simulate slow loading
        
        // Replace this with actual database query for customer data
        $this->customerData = [
            'customer_name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'phone' => '+1234567890',
            'address' => '123 Main St, City, State'
        ];
    }

    public function render()
    {
        return view('livewire.tickets.customer');
    }
}
