<?php

namespace App\Livewire\Tickets;

use Livewire\Component;

class Policy extends Component
{
    public ?int $ticketId = null;
    public array $policyData = [];

    public function mount(?int $ticketId = null)
    {
        $this->ticketId = $ticketId;
    }

    public function loadPolicyData()
    {
        if (!$this->ticketId) {
            return;
        }

        sleep(rand(1,8));

        $this->policyData = [
            'policy_number' => 'POL-' . $this->ticketId,
            'policy_type' => 'Comprehensive',
            'coverage' => 'Full Coverage',
            'expiry_date' => '2024-12-31'
        ];
    }

    public function render()
    {
        return view('livewire.tickets.policy');
    }
}
