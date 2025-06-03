<?php

namespace App\Livewire\Tickets;

use Livewire\Component;

class Customer extends Component
{
    public ?int $ticketId;
    public array $customerData = [];

    public function mount(int $ticketId)
    {
        $this->ticketId = $ticketId;
        
        $this->customerData = [
            'customer_name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'phone' => '+1234567890',
            'address' => '123 Main St, City, State'
        ];
    }

    public function placeholder()
    {
        return view('livewire.placeholders.content-holder');
    }

    public function render()
    {
        return view('livewire.tickets.customer');
    }
}
