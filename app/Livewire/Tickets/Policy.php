<?php

namespace App\Livewire\Tickets;

use Livewire\Component;

class Policy extends Component
{
    public ?int $ticketId = null;
    public bool $isLoading = false;
    public array $policyData = [];

    public function mount(?int $ticketId = null)
    {
        $this->ticketId = $ticketId;
        // Don't load data in mount - let it load lazily
    }

    public function loadPolicyData()
    {
        if (!$this->ticketId) {
            return;
        }

        $this->isLoading = true;
        
        // Simulate data loading - replace with actual policy data fetching
        sleep(3); // Simulate slow loading
        
        // Replace this with actual database query for policy data
        $this->policyData = [
            'policy_number' => 'POL-' . $this->ticketId,
            'policy_type' => 'Comprehensive',
            'coverage' => 'Full Coverage',
            'expiry_date' => '2024-12-31'
        ];
        
        $this->isLoading = false;
    }

    public function render()
    {
        return view('livewire.tickets.policy');
    }
}
